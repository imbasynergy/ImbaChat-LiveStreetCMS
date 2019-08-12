
<script src="https://dev2.imbachat.com/imbachat/v1/{Config::get('plugin.imba_chat_widget.data.dev_id')}/widget"></script>
<script>
    function imbachatWidget(){
        if(!window.ImbaChat){
            return setTimeout(imbachatWidget, 50);
        }

        window.ImbaChat.load({$settings})
        $('.user-profile-user').append('<div style="cursor: pointer;display: inline-block;margin-top: 21px;margin-left: 25px;padding: 5px;border-radius: 3px;background: #4ec4ff;color: white;" id="Imbachat-b-openDialog">Написать</div>');
        $('#Imbachat-b-openDialog').on('click', function(){
        	var id = $('.js-wall-default').attr('data-user-id'); 
        	imbaChat.openDialog(id);
        });
    }
    imbachatWidget();
</script>