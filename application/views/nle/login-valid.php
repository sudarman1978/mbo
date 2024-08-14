<script>
alert("AUP");
    if (window.opener != null && !window.opener.closed) {
        const email = window.opener.setValue('Valid')
    }
    window.close()
</script>
