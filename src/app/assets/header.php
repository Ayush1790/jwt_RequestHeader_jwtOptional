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
                                                "product/addproduct",
                                                "Add Product"
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

    <li class="list-inline-item col">
        <form action="#" method='post'>
            <select name="language" id="" onchange='this.form.submit()'>
                <option selected><?php echo $_POST['language'] ?></option>
                <option value="en">English</option>
                <option value="fr">french</option>
            </select>
        </form>
    </li>

</ul>
