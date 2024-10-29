<?php if ( !$user_ID ) : ?>
    <p><img id="auth-image" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/authimage/authimage-inc/image.veriword.php" alt="Verification Image" /></p>
    <p><label for="code">Word Verification (<a href="#" onclick="document.getElementById('auth-image').src+='?'+Math.random();return false" title="Generate another Captcha Word Verification image.">can't read it? try another!</a>)</label></p>
    <p>Please type the letters you see in the picture.</p>
    <p><input name="code" id="code" type="text" class="text" tabindex="5" /></p>
<?php endif; ?>
