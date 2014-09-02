<?php if (Configure::read('Google.SiteSearch.engine_id')): ?>
<script>
  (function() {
    var cx = '<?php echo Configure::read('Google.SiteSearch.engine_id'); ?>';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<?php endif; ?>

<?php if (Configure::read('Google.Analytics.tracking_id')): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga(
  	'create',
  	'<?php echo Configure::read('Google.Analytics.tracking_id'); ?>',
  	'<?php echo Configure::read('Google.Analytics.domain'); ?>'
  );
  ga('send', 'pageview');
</script>
<?php endif; ?>
