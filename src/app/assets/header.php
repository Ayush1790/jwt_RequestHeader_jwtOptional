<ul class='row list-inline list-unstyled  display-7 bg-primary text-light bg-dark p-3'>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "signup",
                                                "Register Here!"
                                            ]
                                        ); ?></li>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "login",
                                                "Login Here!"
                                            ]
                                        ); ?></li>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "product",
                                                "Products"
                                            ]
                                        ); ?></li>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "product/getorder",
                                                "My Orders"
                                            ]
                                        ); ?></li>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "setting",
                                                "My Setting"
                                            ]
                                        ); ?></li>
    <li class="list-inline-item col"><?php echo Phalcon\Tag::linkTo(
                                            [
                                                "logout",
                                                "Logout"
                                            ]
                                        ); ?></li>
</ul>
