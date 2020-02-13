
<script src="https://imbachat.com/imbachat/v1/{Config::get('plugin.imba_chat_widget.data.dev_id')}/widget"></script>
<script>
    function imbachatWidget(){
        if(!window.ImbaChat){
            return setTimeout(imbachatWidget, 50);
        }
        let params = {$settings};
        window.ImbaChat.load(params);
        $('#Imbachat-b-openDialog').on('click', function(){
            var id = $(this).attr('user_id');
            if(id){
                imbaChat.openDialog(id);
            }
            else{
        	   var id = $('.js-wall-default').attr('data-user-id');
               imbaChat.openDialog(id);
            }
        	
        });
    }
    imbachatWidget();
</script>
