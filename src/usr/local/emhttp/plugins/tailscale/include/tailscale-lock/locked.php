<h3><?= _("Tailscale Lock") ?></h3>

<p>
<?= _('Your tailnet has lock enabled and the current node is not signed') ?>.
<strong><?= _('This node cannot communicate with the tailnet') ?>.</strong>
</p>

<p><?= _('To allow this node to communicate, you will need to trust the following key from a signing node') ?>:</p>

<pre><?= $tailscale_output['lock_nodekey']; ?></pre>