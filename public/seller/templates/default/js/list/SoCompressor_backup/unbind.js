  $(function(){

    function unbind(array_data,type){

        if(type=='qq'){

          var url = ApiUrl + "/index.php?con=member_bind&fun=qqunbind";

        }else{

           var url = ApiUrl + "/index.php?con=member_bind&fun=sinaunbind";

        }

       $.ajax({

              url:url,

              type: "post",

              dataType: "json",

              data:array_data,

            success: function(e) {

                    if (!e.datas.error) {

                      

                        layer.open({

                            content:'解绑成功!',

                            time:1

                        });

                        delCookie("key");

                        delCookie("cart_count");

                        setTimeout(function() {



                            window.location.href = WapUrl + "/tmpl/member/login.html";;

                        },

                        0.5)

                    } else {

                         layer.open({

                            content:e.datas.error,

                            time:1.5

                        });

                    }

            }

        })

    }

    $('.jcbtn').click(function(){

          var type = $(this).attr('type');

           var array_data ={

              is_editpw:'no'

           }

           layer.open({

            content: '您确定要解除绑定吗？',

            btn: ['嗯', '不要'],

            yes: function(index){

              if(type=='qq'){

                 unbind(array_data,'qq');

               }else{

                 unbind(array_data,'sina');

               }

               

                layer.close(index);

            }

        });

    })

    $('#nextform').click(function(){

       var array_data ={

              is_editpw:'yes',

              'new_password':$("#new_password").val(),

              'confirm_password':$("#confirm_password").val()

           }

           layer.open({

            content: '您确定要解除绑定吗？',

            btn: ['嗯', '不要'],

            yes: function(index){

                if(type=='qq'){

                   unbind(array_data,'qq');

                 }else{

                   unbind(array_data,'sina');

                 }

                layer.close(index);

            }

        });

    })

  })