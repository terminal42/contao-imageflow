
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->divId; ?>" class="imageflow"> 
<?php foreach( $this->images as $arrImage ): ?>
	<img src="<?php echo $arrImage['src']; ?>" longdesc="<?php echo ($this->fullsize ? $arrImage['href'] : $arrImage['link']); ?>" width="<?php echo $arrImage['width']; ?>" height="<?php echo $arrImage['height']; ?>" alt="<?php echo $arrImage['alt']; ?>" />
<?php endforeach; ?>
</div>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
	window.addEvent('domready', function()
	{
		new ImageFlow().init({ 
			ImageFlowID: '<?php echo $this->divId; ?>',
			reflectPath: '<?php echo $this->reflectPath; ?>',
			reflections: <?php echo $this->reflections; ?>,
			reflectionP: <?php echo $this->reflectionP; ?>,
			reflectionPNG: <?php echo $this->reflectionPNG; ?>,
			reflectionGET: '<?php echo $this->reflectionGET; ?>',
			imageFocusMax: <?php echo $this->imageFocusMax; ?>,
			startID: <?php echo $this->startID; ?>,
			animationSpeed: <?php echo $this->animationSpeed; ?>,
			<?php if ($this->parameters): foreach( $this->parameters as $arrParameter): ?>
			<?php echo $arrParameter[0] . ': ' . (($arrParameter[1] == 1) ? 'true' : $arrParameter[1]) . ",\n"; ?>
			<?php endforeach; endif; ?>
			<?php if ($this->fullsize): ?>
			onClick: function(el) { <?php echo $this->slimbox ? 'Slimbox.open' : 'Lightbox.show'; ?>(this.getAttribute('longdesc'), this.getAttribute('alt')); }
			<?php else: ?>onClick: function() { window.open(this.getAttribute('longdesc')); }<?php endif; ?>
			
		});
	});
//--><!]]>
</script>

</div>

