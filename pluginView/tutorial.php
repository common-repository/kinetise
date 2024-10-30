<div id="kinetise-plugin-container">
    <h1>
        <a href="https://www.kinetise.com" target="_blank"><span></span></a>
        <span>Tutorial</span>
    </h1>

    <h2 id="kinetise-tutorial-header" class="tutorial-header" data-container="first-tutorial">
        <span data-container="first-tutorial">Displaying list of pages / posts / categories</span>
        <span data-container="third-tutorial">Add new post</span>
        <span data-container="second-tutorial">Add and display post comments</span>
        <span data-container="basic-app-tutorial">Basic Wordpress App with Kinetise</span>
    </h2>

    <div class="row-3" id="first-tutorial">
        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/1_a.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Select list widget to start.</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/1_d.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Select "From Wordpress" option in the list</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/1_b.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>In the settings window, select "Online", pick "From Wordpress" option and paste one of the links below:</p>
            <p>
                <strong>Posts</strong><br>
                <code><?php echo \site_url(); ?>/?kinetiseapi=posts</code>
            </p>
            <p>
                <strong>Pages</strong><br>
                <code><?php echo \site_url(); ?>/?kinetiseapi=pages</code>
            </p>
            <p>
                <strong>Categories</strong><br>
                <code><?php echo \site_url(); ?>/?kinetiseapi=categories</code>
            </p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/1_c.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>When you click "OK" button the list should be refreshed. Please feel free to adapt view for your
                needs.</p>
        </div>
    </div>

    <div class="row-3" id="third-tutorial">
        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/4_a.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Create a new form with following input IDs: "author", "title", "category", "content",
                "image".</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/4_b.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>In the settings window select API URL and paste the following link:</p>
            <p>
                <code><?php echo \site_url(); ?>/?kinetiseapi=posts:add</code>
            </p>
        </div>
    </div>

    <div class="row-3" id="second-tutorial">
        <h2>Display list of comments:</h2>
        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/2_a.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Create new screen.</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/2_b.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>On main screen use list widget with link bellow:</p>
            <p>
                <strong>Posts</strong><br>
                <code><?php echo \site_url(); ?>/?kinetiseapi=posts</code>
            </p>
            <p>In the events section select as action previously created screen.</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/2_c.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Go to new previously created screen. Drop list widget and check <strong>"Dynamic"</strong> in setings.
                Select <strong>"COMMENTS_URL"</strong> from the dropdown list.</p>
        </div>
    </div>
    <div class="row-3" id="second-tutorial">
        <h2>Add new comment:</h2>
        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/2_d.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Create a new form on the comments list screen with following input IDs: "author", "email", "website",
                "body".</p>
        </div>

        <div class="row-col">
            <img src="<?php echo plugins_url('images/tutorial/2_e.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                 width="300px" height="300px">
            <p>Select <strong>"Dynamic"</strong> form address. From the dropdown list in API URL select <strong>"COMMENTS_URL_ADD".</strong>
            </p>
        </div>
    </div>

    <div class="row-3" id="basic-app-tutorial">
        <div>
            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_a.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Use list widget.</p>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_b.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>On main screen use list widget with the following link and click <strong>"OK"</strong>:</p>
                <p>
                    <code><?php echo \site_url(); ?>/?kinetiseapi</code>
                </p>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_c.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Use navigation tree on the left side and delete <strong>"Image"</strong> widget from list item</p>
            </div>
        </div>

        <div>
            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_d.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Then expand <strong>"Vertical Container"</strong> and remove 2nd <strong>"Text"</strong> widget</p>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_e.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Time to add some screens. Let create three of them:</p>
                <code><strong>Pages</strong>, <strong>Posts</strong> and <strong>Categories</strong></code>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_i.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Go to <strong>"Main screen"</strong> and from navigation tree edit <strong>"List"</strong>
                    widget</p>
            </div>
        </div>

        <div>
            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_f.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>In <strong>"Settings"</strong> window under <strong>"Extra options"</strong> section create three item templates - "Pages", "Categories" and "Posts".</p>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_j.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Eeach item template should filter by <strong> "title" </strong> field and redirect to the corresponding details screen.</p>
            </div>

            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_g.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Then using right mouse button click on <strong>"Pages"</strong> item inside list. You will be
                    moved to details screen for pages.</p>
            </div>
        </div>
        <div>
            <div class="row-col">
                <img src="<?php echo plugins_url('images/tutorial/3_h.png', KINETISE_ROOT . DS . 'kinetise.php') ?>"
                     width="300px" height="300px">
                <p>Place
                    <string>"List"</string>
                    widget on each new detail screen. In the <strong>"Settings"</strong> window select <strong>"Dynamic"</strong> source. From here
                    you can select <strong>"URL"</strong> option to load your Wordpress pages.
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var addClass = function addClass(el, className) {
        var regex = new RegExp(className);

        if (el.className.match(regex)) {
            return;
        }

        el.className += ' ' + className;
    };

    var removeClass = function removeClass(el, className) {
        var regex = new RegExp('\\s' + className);

        if (!el.className.match(regex)) {
            return;
        }

        el.className = el.className.replace(regex, '');
    };

    var showSection = function (id, elements) {
        for (var j = 0; j < elements.length; j++) {
            elements[j].style.display = elements[j].id == id ? 'block' : 'none';
        }

        var sections = document
            .getElementById('kinetise-tutorial-header')
            .getElementsByTagName('span');

        for (var j = 0; j < sections.length; j++) {
            addClass(sections[j], 'not-active');
        }
    };

    window.addEventListener('load', function () {
        var i, j, header, sections, containers = document.getElementsByClassName('row-3');

        for (i = 0; i < containers.length; i++) {
            if (i != 0) {
                containers[i].style.display = 'none';
            }
        }

        header = document.getElementById('kinetise-tutorial-header');
        sections = header.getElementsByTagName('span');

        for (j = 0; j < sections.length; j++) {
            if (j != 0) {
                addClass(sections[j], 'not-active');
            }

            sections[j].addEventListener('click', function () {
                var current = this;
                if (current.className.indexOf('not-active') == -1) {
                    return;
                }

                showSection(current.getAttribute('data-container'), containers);
                removeClass(current, 'not-active');
            });
        }
    });
</script>