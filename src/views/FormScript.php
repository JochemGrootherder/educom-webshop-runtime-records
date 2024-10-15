<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    $(document).ready( function(e) {
        e.preventDefault();
            $("#postForm").click( function(e) {
                $.ajax({
                    type: 'post',
                    data: $('form').serialize(),
                    url: 'index.php',
                    success: function () {
                }
          });
            })
      }) 
</script>