$(document).ready(function () {
        $('.login-form').submit(function () {
            var login = $('#email').val();
            var pass = $('#senha').val();
            $.ajax({//Função AJAX
                url: "/validaLogin",
                type: "post",
                data: {email: login, senha: pass},

                success: function (result) {
                    if (result == 1) {
                        location.href = "/";
                    } else {
                $('#aviso').html('Usuário ou senha errados!');
                    }
                },
                error: function () {
                    alert('Erro 664!');
                }
            });
            return false;
        });
});

