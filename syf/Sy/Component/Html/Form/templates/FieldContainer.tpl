<<?php echo $TAG_NAME ?><?php if (isset($BLOCK_ATTRIBUTES)): foreach ($BLOCK_ATTRIBUTES as $a): ?> <?php echo $a['NAME'] ?>="<?php echo $a['VALUE'] ?>"<?php endforeach; endif ?>>
<?php if (isset($BLOCK_CONTENT)): foreach ($BLOCK_CONTENT as $c): echo $c['ELEMENT']; endforeach; endif; ?>
</<?php echo $TAG_NAME ?>>
