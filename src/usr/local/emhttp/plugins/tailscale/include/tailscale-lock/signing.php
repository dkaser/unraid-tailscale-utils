<h3><?= _('Tailscale Lock') ?></h3>
<h4><?= _('Sign Nodes') ?></h4>
<p>
    <?= _('This is a signing node for the tailnet') ?>.
</p>
<p>
    <?= _('The following nodes are currently locked out of the tailnet') ?>. 
    <?= _('Check the box for any nodes you wish to add, then click the Sign button, to add the node to the tailnet') ?>.</p>
<form markdown="1" method="POST" action="/update.php" target="progressFrame">
<input type="hidden" name="#command" value="/usr/local/emhttp/plugins/tailscale/approve-nodes.php" />
<table style="margin-top: 5px;">
<?php
foreach ($tailscale_output['lock_pending'] as $lockHost => $lockKey) {
    echo "<tr><td><input type='checkbox' name='#arg[]' value='{$lockKey}' /></td><td>{$lockHost}<br />{$lockKey}</td></tr>";
}
?>
</table>

<input type="submit" name="#apply" value="<?= _('Sign') ?>">
</form>