<?php
function printAdminWPCF7LocawebPage(){
	register_post_type(
		'prwpcf7locaweb',
		array(
			'labels' => array(
				'name' => 'WPCF7Locaweb',
				'singular_name' => 'WPCF7Locaweb',
			),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'supports' => array(''),
		)
	);
}
function printAdminWPCF7LocawebAddMetaBox(){
	add_meta_box('wpcf7locawebInfo', "Integração do WPCF7Locaweb", 'printAdminWPCF7LocawebInfo', 'prwpcf7locaweb', 'normal', 'high');
}
function printAdminWPCF7LocawebInfo($post){
	$values = get_post_meta($post->ID, 'prWPCF7Locaweb', true);
	$prWPCF7LocawebNome = $values['nome'];
	$prWPCF7LocawebEmail = $values['email'];
	$prWPCF7LocawebDataDeNascimento = $values['datadenascimento'];
	$prWPCF7LocawebLista = $values['lista'];
	$prWPCF7LocawebHostname = $values['hostname'];
	$prWPCF7LocawebLogin = $values['login'];
	$prWPCF7LocawebChaveAPI = $values['chaveapi'];

	/* RECUPERA INFORMAÇÕES DOS FORMULÁRIOS */
	$args = array(
		'post_type' => 'wpcf7_contact_form',
		'numberposts' => -1
	);
	$forms = get_posts($args);

	if($post->post_title):
		preg_match_all('/\[(.*?)\]/', get_post_meta($post->post_title, '_form', true), $array_wpcf7_form, PREG_SET_ORDER);
		foreach($array_wpcf7_form as $array_wpcf7_form_fields):
			$campo = explode(' ', $array_wpcf7_form_fields[1]);
			if($campo[0] != 'submit')
				$array_campos[] = $campo[1];
		endforeach;
	endif;

	wp_nonce_field( 'prwpcf7locaweb_nonce', 'metabox_nonce' );
?>
<div class="poststuff">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					Formulário
				</th>
				<td>
					<select name="post_title" id="post_title" class="postform">
						<option value="">---</option>
						<?php foreach($forms as $key => $form): ?>
							<option class="level-<?php echo $key; ?>" value="<?php echo $form->ID; ?>" <?php selected($post->post_title, $form->ID);?>><?php echo $form->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Campo Nome
				</th>
				<td>
					<select name="prWPCF7Locaweb[nome]" id="prWPCF7LocawebNome" class="postform">
						<option value="">---</option>
						<?php foreach($array_campos as $key => $campo): ?>
							<option class="level-<?php echo $key; ?>" <?php selected($prWPCF7LocawebNome, $campo); ?>><?php echo $campo; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Campo Email
				</th>
				<td>
					<select name="prWPCF7Locaweb[email]" id="prWPCF7LocawebEmail" class="postform">
						<option value="">---</option>
						<?php foreach($array_campos as $key => $campo): ?>
							<option class="level-<?php echo $key; ?>" <?php selected($prWPCF7LocawebEmail, $campo); ?>><?php echo $campo; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Campo Data de Nascimento
				</th>
				<td>
					<select name="prWPCF7Locaweb[datanascimento]" id="prWPCF7LocawebDataNascimento" class="postform">
						<option value="">---</option>
						<?php foreach($array_campos as $key => $campo): ?>
							<option class="level-<?php echo $key; ?>" <?php selected($prWPCF7LocawebDataNascimento, $campo); ?>><?php echo $campo; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Código da Lista
				</th>
				<td>
					<input type="text" required="required" name="prWPCF7Locaweb[lista]" id="prWPCF7LocawebLista" value="<?php echo $prWPCF7LocawebLista; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Nome do Host (Hostname)
				</th>
				<td>
					<input type="text" required="required" name="prWPCF7Locaweb[hostname]" id="prWPCF7LocawebHostname" value="<?php echo $prWPCF7LocawebHostname; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Login
				</th>
				<td>
					<input type="text" required="required" name="prWPCF7Locaweb[login]" id="prWPCF7LocawebLogin" value="<?php echo $prWPCF7LocawebLogin; ?>" class="regular-text code">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					Chave da API
				</th>
				<td>
					<input type="text" required="required" name="prWPCF7Locaweb[chaveapi]" id="prWPCF7LocawebChaveAPI" value="<?php echo $prWPCF7LocawebChaveAPI; ?>" class="regular-text code">
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
}
function printAdminWPCF7LocawebSave($post_id){
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;
	if( !isset( $_POST['metabox_nonce'] ) || !wp_verify_nonce( $_POST['metabox_nonce'], 'prwpcf7locaweb_nonce' ) )
		return $post_id;
	if( !current_user_can( 'edit_post' ) )
		return $post_id;
	if( get_post_type($post_id) != 'prwpcf7locaweb' )
		return $post_id;
	update_post_meta( $post_id, 'prWPCF7Locaweb', $_POST['prWPCF7Locaweb']);
}
function printAdminWPCF7LocawebColumns($columns) {
	return $columns = array(
		'cb' => '<input type="checkbox" />',
		'prform' => 'Formulário',
		'prnome' => 'Nome',
		'premail' => 'Email',
		'prnascimento' => 'Data de Nascimento'
	);
}
function printAdminWPCF7LocawebCustomColumns($column, $post_id){
	switch ($column){
		case 'prform':
			$prWPCF7Locaweb = get_the_title(get_the_title($post_id));
			echo '<strong><a title="Editar Consulta" href="'.admin_url('post.php?post='.$post_id.'&amp;action=edit').'" class="row-title">'.$prWPCF7Locaweb.'</a></strong>';
			echo '<div class="row-actions"><span class="edit"><a title="Editar Consulta" href="'.admin_url('post.php?post='.$post_id.'&amp;action=edit').'">Editar</a> | </span><span class="trash"><a href="'.get_delete_post_link($post_id).'" title="Mover para lixeira" class="submitdelete">Apagar</a></span></div>';
			break;
		case 'prnome':
			$value = get_post_meta($post_id, 'prWPCF7Locaweb', true);
			if($value['nome'])
				echo '['.$value['nome'].']';
			break;
		case 'premail':
			$value = get_post_meta($post_id, 'prWPCF7Locaweb', true);
			if($value['email'])
				echo '['.$value['email'].']';
			break;
		case 'prdatanascimento':
			$value = get_post_meta($post_id, 'prWPCF7Locaweb', true);
			if($value['datanascimento'])
				echo '['.$value['datanascimento'].']';
			break;
	}
}

add_action( 'init', 'printAdminWPCF7LocawebPage' );
add_action( 'add_meta_boxes', 'printAdminWPCF7LocawebAddMetaBox');
add_action( 'save_post', 'printAdminWPCF7LocawebSave' );
add_filter( 'manage_prwpcf7locaweb_posts_columns' , 'printAdminWPCF7LocawebColumns');
add_action( 'manage_prwpcf7locaweb_posts_custom_column' , 'printAdminWPCF7LocawebCustomColumns', 10, 2);

add_action( 'wp_ajax_ajax_campos', 'ajax_campos_callback' );
add_action( 'wp_ajax_nopriv_ajax_campos', 'ajax_campos_callback' );

function ajax_campos_callback() {
	global $wpdb; // this is how you get access to the database

	$form_id = $_GET['form_id'];

	preg_match_all('/\[(.*?)\]/', get_post_meta($form_id, '_form', true), $array_wpcf7_form, PREG_SET_ORDER);
	foreach($array_wpcf7_form as $array_wpcf7_form_fields):
		$campo = explode(' ', $array_wpcf7_form_fields[1]);
		if($campo[0] != 'submit')
			$array_campos[] = $campo[1];
	endforeach;

	echo json_encode($array_campos);

	die(); // this is required to return a proper result
}

add_action( 'admin_footer', 'my_action_javascript' );

function my_action_javascript() {
?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		$('#post_title').change(function(){
			$.getJSON("<?php echo admin_url( 'admin-ajax.php' ); ?>",
				{
					'action' : 'ajax_campos',
					'form_id': $(this).val()
				},
				function(data){
					var html = '<option value="">---</option>';
					var len = data.length;
					for(var i=0; i<len; i++){
						html += '<option>' + data[i] + '</option>';
					}
					$("#prWPCF7LocawebNome").empty().append(html);
					$("#prWPCF7LocawebEmail").empty().append(html);
					$("#prWPCF7LocawebDataNascimento").empty().append(html);
				}
			);
		});
	});
	</script>
<?php
}
