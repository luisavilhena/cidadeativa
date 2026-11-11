<?php
/**
 * Converte campos do Carbon Fields gravados no formato antigo (Carbon 1.x)
 * para o formato do Carbon Fields 3, sem apagar os valores antigos.
 *
 * Formato antigo → formato novo:
 *   Associação: _campo = array de IDs
 *               → _campo|||N|value = "post:{post_type}:{id}" (+ id, type, subtype)
 *   Repetidor:  _campo_-_subcampo_N
 *               → _campo|||N|value = "_" e _campo|subcampo|N|0|value
 *
 * Regras:
 *   - Só converte quando o post ainda não tem dados no formato novo.
 *     Se tiver, mantém o novo e só avisa quando os dois forem diferentes.
 *   - Remove o marcador "|||0|_empty" (que faz o Carbon 3 mostrar o campo vazio).
 *   - Não apaga nenhum valor antigo.
 *
 * Uso (PHP do MAMP, na raiz do WordPress):
 *   php wp-content/themes/cidade-ativa-3/tools/migrate-legacy-carbon-fields.php           # simulação
 *   php wp-content/themes/cidade-ativa-3/tools/migrate-legacy-carbon-fields.php --apply   # grava
 *
 * @package cidade-ativa-3
 */

if ( 'cli' !== PHP_SAPI ) {
	exit;
}

define( 'WP_USE_THEMES', false );
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost:8888';
require getcwd() . '/wp-load.php';

global $wpdb;

$apply = in_array( '--apply', $argv, true );
$table = $wpdb->postmeta;
$stats = array( 'associacoes' => 0, 'repetidores' => 0, 'ja_no_novo' => 0, 'marcadores' => 0 );

echo $apply ? "=== GRAVANDO ===\n" : "=== SIMULAÇÃO (nada será gravado; use --apply) ===\n";

/** Há dados no formato novo (não vazios) para este campo? */
function ca3_has_v3_data( $post_id, $key ) {
	global $wpdb;

	return (bool) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT 1 FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE %s AND meta_key NOT LIKE %s LIMIT 1",
			$post_id,
			$wpdb->esc_like( '_' . $key . '|' ) . '%',
			'%|\_empty'
		)
	);
}

function ca3_add_meta( $post_id, $key, $value ) {
	global $wpdb, $apply;

	if ( $apply ) {
		$wpdb->insert( $wpdb->postmeta, array( 'post_id' => $post_id, 'meta_key' => $key, 'meta_value' => $value ) );
	}
}

function ca3_remove_empty_marker( $post_id, $key ) {
	global $wpdb, $apply, $stats;

	$marker = '_' . $key . '|||0|_empty';
	$exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s", $post_id, $marker ) );

	if ( $exists ) {
		$stats['marcadores']++;
		if ( $apply ) {
			$wpdb->delete( $wpdb->postmeta, array( 'post_id' => $post_id, 'meta_key' => $marker ) );
		}
	}
}

/* --------------------------------------------------------------------------
 * 1. Associações (array de IDs serializado)
 * ----------------------------------------------------------------------- */

$association_keys = array( 'ca_iniciativa_eixos', 'iniciativas' );

foreach ( $association_keys as $key ) {
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM {$table} WHERE meta_key = %s", '_' . $key ) );

	foreach ( $rows as $row ) {
		$ids = maybe_unserialize( $row->meta_value );
		if ( ! is_array( $ids ) || ! $ids ) {
			continue;
		}

		$label = sprintf( '#%d "%s" → %s', $row->post_id, get_the_title( $row->post_id ), $key );

		if ( ca3_has_v3_data( $row->post_id, $key ) ) {
			$stats['ja_no_novo']++;
			$v3_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM {$table} WHERE post_id = %d AND meta_key LIKE %s", $row->post_id, $wpdb->esc_like( '_' . $key . '|||' ) . '%|id' ) );
			$missing = array_diff( array_map( 'strval', $ids ), $v3_ids );
			if ( $missing ) {
				echo "  ATENÇÃO {$label}: já tem dados no formato novo; o formato antigo tem também os IDs " . implode( ', ', $missing ) . " (mantido o novo)\n";
			}
			continue;
		}

		$index = 0;
		$names = array();
		foreach ( $ids as $id ) {
			$id   = (int) $id;
			$type = get_post_type( $id );

			if ( ! $type ) {
				echo "  AVISO {$label}: o post #{$id} não existe mais, ignorado\n";
				continue;
			}

			$base = '_' . $key . '|||' . $index . '|';
			ca3_add_meta( $row->post_id, $base . 'value', "post:{$type}:{$id}" );
			ca3_add_meta( $row->post_id, $base . 'type', 'post' );
			ca3_add_meta( $row->post_id, $base . 'subtype', $type );
			ca3_add_meta( $row->post_id, $base . 'id', (string) $id );
			$names[] = get_the_title( $id );
			$index++;
		}

		if ( $index ) {
			ca3_remove_empty_marker( $row->post_id, $key );
			$stats['associacoes']++;
			echo "  {$label}: " . implode( ' | ', $names ) . "\n";
		}
	}
}

/* --------------------------------------------------------------------------
 * 2. Repetidores (_campo_-_subcampo_N)
 * ----------------------------------------------------------------------- */

$rows = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM {$table} WHERE meta_key LIKE '%\\_-\\_%' ORDER BY post_id, meta_id" );
$groups = array();

foreach ( $rows as $row ) {
	if ( ! preg_match( '/^_(.+?)_-_(.+)_(\d+)$/', $row->meta_key, $m ) || false !== strpos( $m[2], '_-_' ) ) {
		echo "  AVISO: chave não reconhecida, ignorada: #{$row->post_id} {$row->meta_key}\n";
		continue;
	}
	$groups[ $row->post_id ][ $m[1] ][ (int) $m[3] ][ $m[2] ] = $row->meta_value;
}

foreach ( $groups as $post_id => $fields ) {
	foreach ( $fields as $key => $entries ) {
		$label = sprintf( '#%d "%s" → %s', $post_id, get_the_title( $post_id ), $key );

		if ( ca3_has_v3_data( $post_id, $key ) ) {
			$stats['ja_no_novo']++;
			$v3_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE post_id = %d AND meta_key LIKE %s", $post_id, $wpdb->esc_like( '_' . $key . '|||' ) . '%|value' ) );
			if ( $v3_count < count( $entries ) ) {
				echo "  ATENÇÃO {$label}: já tem {$v3_count} item(ns) no formato novo e " . count( $entries ) . " no antigo (mantido o novo)\n";
			}
			continue;
		}

		ksort( $entries );
		$index = 0;
		foreach ( $entries as $subfields ) {
			ca3_add_meta( $post_id, '_' . $key . '|||' . $index . '|value', '_' );
			foreach ( $subfields as $subfield => $value ) {
				ca3_add_meta( $post_id, '_' . $key . '|' . $subfield . '|' . $index . '|0|value', $value );
			}
			$index++;
		}

		ca3_remove_empty_marker( $post_id, $key );
		$stats['repetidores']++;
		echo "  {$label}: {$index} item(ns)\n";
	}
}

echo "\nResumo: {$stats['associacoes']} associações e {$stats['repetidores']} repetidores convertidos; "
	. "{$stats['ja_no_novo']} campos já estavam no formato novo; {$stats['marcadores']} marcadores de vazio removidos.\n";

if ( $apply ) {
	wp_cache_flush();
}
