<h3>Tailscale Lock</h3>

<p>Your tailnet has lock enabled and the current node is signed. This node can communicate with the tailnet.</p>

<p>If you wish to make this a signing node, you will need to trust the following key from a signing node:</p>

<pre><?= $tailscale_output['lock_pubkey']; ?></pre>

