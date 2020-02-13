
<script src="https://imbachat.com/imbachat/v1/{Config::get('plugin.imba_chat_widget.data.dev_id')}/widget"></script>
<script>
    function imbachatWidget(){
        if(!window.ImbaChat){
            return setTimeout(imbachatWidget, 50);
        }
        let params = {$settings};
        params['onInitSuccess'] = () =>{
            imbaChat.openChat() 
            imbaChat.addToRoom({
                pipe:'c100',
                title:'Conf 100',
                'is_public': 1,
                type:imbaChat.room_type.conference,
                'users_ids':[
                    {
                        user_id:params.user_id
                    }
                ]
            })
        }
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
