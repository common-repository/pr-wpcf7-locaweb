<?php
function postPRWPCF7Locaweb(){
	global $post;

	$args = array(
		'post_type' => 'prwpcf7locaweb',
		'numberposts' => -1
	);
	$wpcf7locawebforms = get_posts($args);
	if($wpcf7locawebforms):
	?>
		<script>
		jQuery(document).ready(function($){
			<?php
			foreach($wpcf7locawebforms as $form):
				$values = get_post_meta($form->ID, 'prWPCF7Locaweb', true);
				$prWPCF7LocawebNome = $values['nome'];
				$prWPCF7LocawebEmail = $values['email'];
				$prWPCF7LocawebDataNascimento = $values['datadenascimento'];

				if( WPCF7_VERSION > 3.6 ) {
					if ( in_the_loop() ) {
						$unit_tag = sprintf( 'wpcf7-f%1$d-p%2$d-o%3$d',
							absint( $form->post_title ), get_the_ID(), 1 );
					} else {
						$unit_tag = sprintf( 'wpcf7-f%1$d-o%2$d',
							absint( $form->post_title ), 1 );
					}
				} else {
					$paginainicial = get_option('page_on_front');
					$tipodepagina = 'p';
					if($paginainicial == 0 && (is_home() || is_front_page())) $tipodepagina = 't';

					$postid = $post->ID;
					if($paginainicial == 0 && (is_home() || is_front_page())) $postid = '1';

					$unit_tag = 'wpcf7-f' . $form->post_title . '-' . $tipodepagina.$postid . '-o1';
				}

			?>
			$(document).on('click', '#<?php echo $unit_tag; ?> .wpcf7-submit', function(){
					console.log('Integração WPCF7 Locaweb!');
					<?php if($prWPCF7LocawebNome): ?>
						var prnome = $(this).parents('.wpcf7-form').find('input[name=<?php echo $prWPCF7LocawebNome; ?>]').val();
					<?php endif; ?>
					<?php if($prWPCF7LocawebEmail): ?>
						var premail = $(this).parents('.wpcf7-form').find('input[name=<?php echo $prWPCF7LocawebEmail; ?>]').val();
					<?php endif; ?>
					<?php if($prWPCF7LocawebTelefone): ?>
						var prtelefone = $(this).parents('.wpcf7-form').find('input[name=<?php echo $prWPCF7LocawebTelefone; ?>]').val();
					<?php endif; ?>
					<?php if($prWPCF7LocawebDataNascimento): ?>
						var prdatanascimento = $(this).parents('.wpcf7-form').find('input[name=<?php echo $prWPCF7LocawebDataNascimento; ?>]').val();
					<?php endif; ?>
					<?php if($prWPCF7LocawebExtra): ?>
						var prextra = $(this).parents('.wpcf7-form').find('input[name=<?php echo $prWPCF7LocawebExtra; ?>]').val();
					<?php endif; ?>
				$.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", {
					<?php if($prWPCF7LocawebNome): ?>'<?php echo $prWPCF7LocawebNome; ?>': prnome,<?php endif; ?>
					<?php if($prWPCF7LocawebEmail): ?>'<?php echo $prWPCF7LocawebEmail; ?>': premail,<?php endif; ?>
					<?php if($prWPCF7LocawebDataNascimento): ?>'<?php echo $prWPCF7LocawebDataNascimento; ?>': prdatanascimento,<?php endif; ?>
					'form_title': '<?php echo $form->post_title; ?>',
					'form_id': '<?php echo $form->ID; ?>',
					'action': 'ajax_wpcf7locaweb'
				});
			});
			<?php endforeach; ?>
		});
		</script>
	<?php
	endif;
}

add_action( 'wp_head', 'postPRWPCF7Locaweb' );

add_action( 'wp_ajax_ajax_wpcf7locaweb', 'ajax_wpcf7locaweb_callback' );
add_action( 'wp_ajax_nopriv_ajax_wpcf7locaweb', 'ajax_wpcf7locaweb_callback' );

function ajax_wpcf7locaweb_callback() {
	global $wpdb; // this is how you get access to the database

	$values = get_post_meta($_POST['form_id'], 'prWPCF7Locaweb', true);
	$prWPCF7LocawebNome = $_POST[$values['nome']];
	$prWPCF7LocawebEmail = $_POST[$values['email']];
	$prWPCF7LocawebDataNascimento = $_POST[$values['datanascimento']];
	$prWPCF7LocawebLista = $values['lista'];
	$prWPCF7LocawebHostname = $values['hostname'];
	$prWPCF7LocawebLogin = $values['login'];
	$prWPCF7LocawebChaveAPI = $values['chaveapi'];

	require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
	$repositorio = new RepositorioContatos($prWPCF7LocawebHostname, $prWPCF7LocawebLogin, $prWPCF7LocawebChaveAPI);

	$contatos = array();
	array_push(
		$contatos,
		array(
			'nome' => $prWPCF7LocawebNome,
			'email' => $prWPCF7LocawebEmail,
			'datadenascimento' => $prWPCF7LocawebDataNascimento
		)
	);

	$repositorio->importar( $contatos, array( $prWPCF7LocawebLista ) );

	// Testando o resultado de retorno do webservice
	if( $repositorio ) {
		echo "A ação com o email <strong>".$prWPCF7LocawebEmail."</strong> foi efetuada com sucesso!<br>";
	} else {
		echo "Ocorreu um erro com o email <strong>".$prWPCF7LocawebEmail."</strong>";
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		wp_mail( 'contato@paulor.com.br', 'Erro WPCF7 Locaweb - ' . get_bloginfo( 'name' ), "<p>Ocorreu um erro com o email <strong>".$prWPCF7LocawebEmail."</strong></p><p>Site: " . get_bloginfo( 'name' ) ."</p>" );
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	}

	die(); // this is required to return a proper result
}

function set_html_content_type() {
	return 'text/html';
}
