<div style="background-color:#A2C617;padding:20px;margin-left:-20px;bottom:0px;min-height:calc(100vh - 150px)">
	<h1>spareparts.live layer</h1>
	<div style="font-size:1.2em">
		<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" >
			<br/><br/>
			<input type="hidden" name="action" value="spl_save_config"/>
			<label for="splToken">domain access token:</label>
			<input type="text" id="splToken" maxlength="21" name="token" value="<?php echo esc_attr($this->token); ?>"/>
			<input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('sparepartslive-nonce')); ?>"/>
			<button type="submit" style="background-color:#212A72;color:#fff">save</button>
            		<br/><br/>
			<input type="checkbox" id="splHideTab" style="margin-left:152px" name="hidetab" value="hidetab" <?php echo ($this->hidetab ? 'checked="checked"' : ''); ?>/>&nbsp;Hide tab
			<br/><br/>
			<a href="https://spareparts.live" target="_blank" title="Open spareparts.live website in a new tab" style="color:#212A72;text-decoration:none"><span class="dashicons dashicons-arrow-right-alt"></span>&nbsp;to get your domain access token, please register at spareparts.live</a><br/><br/>
			<a href="https://my.spareparts.live" target="_blank" title="Open my.spareparts.live in a new tab" style="color:#212A72;text-decoration:none"><span class="dashicons dashicons-arrow-right-alt"></span>&nbsp;create and manage eCatalogs for your Webshop</a><br/>
		</form>
	</div>
</div>
