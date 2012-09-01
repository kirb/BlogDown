# BlogDown - basic Markdown blog
[MIT licensed.](http://adam.mit-license.org)
## Setup
1. Copy `blog.php`, `blog_admin.php`, `global.php`, `config.php` and `markdown.php` to your site's document root.
2. Modify the settings in `config.php` for your blog. This includes having to set up your site with [Disqus](http://disqus.com).
3. Copy `.htaccess` to your site, or if it already exists, append the contents to the existing .htaccess.
4. Run the following SQL in your database:

        CREATE TABLE `posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` text NOT NULL,
            `posted` datetime NOT NULL,
            `updated` datetime NOT NULL,
            `shorturl` text NOT NULL,
            `post` text NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8 AUTO_INCREMENT=4;

5. Use `htdigest` to create a `.htpasswd` file to secure the admin area, and then edit the `AuthUserFile` line in the `.htaccess.to point to the (absolute) path to the file.
6. That's it; go to http://example.com/blog_admin (obviously replacing example.com with your domain) and have fun!

You'll want to modify the `blogdown_header` and `blogdown_footer` functions in `global.php` to add your own styling, etc. too.