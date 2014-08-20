<?php
/**
 * Provides somewhat-secure access to PHP Info page. The URL must look
 * like: http://site.com/_pi.php?show in order for any output to render.
 */

if (isset($_GET['show'])) {
	phpinfo();
}