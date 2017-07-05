$(document)
            .ready(function () {
                $('.ui.form')
                    .form({
                        fields: {
                            email: {
                                identifier: 'email',
                                rules: [
                                    {
                                        type: 'empty',
                                        prompt: 'Masukkan alamat email'
                                    }
                                ]
                            },
                            password: {
                                identifier: 'password',
                                rules: [
                                    {
                                        type: 'empty',
                                        prompt: 'Masukkan password'
                                    },
                                    {
                                        type: 'length[6]',
                                        prompt: 'Password minimal 6 karakter'
                                    }
                                ]
                            }
                        }
                    })
                    ;
            })
            ;