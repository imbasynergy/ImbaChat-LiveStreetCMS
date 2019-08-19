
<script src="https://imbachat.com/imbachat/v1/{Config::get('plugin.imba_chat_widget.data.dev_id')}/widget"></script>
<script>
    function imbachatWidget(){
        if(!window.ImbaChat){
            return setTimeout(imbachatWidget, 50);
        }

        window.ImbaChat.load({$settings})
        $('#Imbachat-b-openDialog').on('click', function(){
        	var id = $('.js-wall-default').attr('data-user-id'); 
        	imbaChat.openDialog(id);
        });
    }
    imbachatWidget();
</script>