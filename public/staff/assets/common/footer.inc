	</div><!-- End div - main_content -->
        <div class="clear_both"></div>
    </div>
    <div id="footer">
        <div class="page-width">
	<div id="copyright">Copyright<sup>&copy;</sup> The Child Schools <?php echo date("Y") == 2015 ? 2015 : 2015 .' - '. date("Y"); ?></div>
        </div>
    </div><!-- End div - footer -->

<?php if(@$_GET['message'] && @$_GET['type'] || (isset($type) && isset($message) )): ?>
<div id="notification">
	<div class="close">x</div>
	<div class="<?php echo isset($type) ? $type : @$_GET['type']; ?>" ><?php echo isset($message) ? $message : @$_GET['message']; ?></div>
</div>
<?php endif; ?>

<?php foreach ( $common_js_files as $gen_files ): ?>
	<script language="javascript" type="text/javascript" src="<?php echo WEB_ROOT; ?>/assets/js/<?php echo $gen_files; ?>" /></script>
<?php endforeach; ?>
<?php foreach ( $page_js_files as $page_js ): ?>
	<script language="javascript" type="text/javascript" src="<?php echo WEB_ROOT; ?>/staff/assets/js/<?php echo $page_js; ?>" /></script>
<?php endforeach; ?>
</body>
</html>