<?php
// This is a manual implementation of a Google Custom Searchbox,
// normally done with a <gcse:searchbox-only><gcse:searchbox-only> custom element.
// This way preserves the style and loads WAY quicker.
$query = (isset($this->request->query['q']) ? "value=\"{$this->request->query['q']}\"" : "");
?>
<form action="/pages/search" class="search-form">
	<input type="submit" value="search" />
	<input type="search" name="q" placeholder="<?php echo __('Search'); ?>" <?php echo $query; ?>/>
</form>
