<?php

namespace CakeDC\Roadrunner\Test;

use Laminas\Diactoros\ServerRequest;

class ServerRequestHelper
{
    /**
     * @todo need to mock a request
     * @param array $serverParams A list of optional PHP $_SERVER variables to overwrite the defaults.
     * @return \Laminas\Diactoros\ServerRequest
     */
    public function buildRequest(array $serverParams = []): ServerRequest
    {
        return new ServerRequest(
            $this->server($serverParams)
        );
    }

    /**
     * @param array $serverParams
     * @return array
     */
    private function server(array $serverParams): array
    {
        return array_merge([
            'GJS_DEBUG_TOPICS' => 'JS ERROR;JS LOG',
            'LANGUAGE' => 'C',
            'USER' => 'chris',
            'SSH_AGENT_PID' => '3928',
            'XDG_SESSION_TYPE' => 'x11',
            'GIT_ASKPASS' => 'echo',
            'SHLVL' => '1',
            'HOME' => '/home/chris',
            'DESKTOP_SESSION' => 'ubuntu',
            'GNOME_SHELL_SESSION_MODE' => 'ubuntu',
            'GTK_MODULES' => 'gail:atk-bridge',
            'XDG_SEAT_PATH' => '/org/freedesktop/DisplayManager/Seat0',
            'MANAGERPID' => '3765',
            'DBUS_SESSION_BUS_ADDRESS' => 'unix:path=/run/user/1000/bus',
            'COLORTERM' => 'truecolor',
            'COMPOSER_ORIGINAL_INIS' => '/etc/php/8.0/cli/php.ini:/etc/php/8.0/cli/conf.d/10-mysqlnd.ini:/etc/php/8.0/cli/conf.d/10-opcache.ini:/etc/php/8.0/cli/conf.d/10-pdo.ini:/etc/php/8.0/cli/conf.d/15-xml.ini:/etc/php/8.0/cli/conf.d/20-calendar.ini:/etc/php/8.0/cli/conf.d/20-ctype.ini:/etc/php/8.0/cli/conf.d/20-curl.ini:/etc/php/8.0/cli/conf.d/20-dom.ini:/etc/php/8.0/cli/conf.d/20-exif.ini:/etc/php/8.0/cli/conf.d/20-ffi.ini:/etc/php/8.0/cli/conf.d/20-fileinfo.ini:/etc/php/8.0/cli/conf.d/20-ftp.ini:/etc/php/8.0/cli/conf.d/20-gettext.ini:/etc/php/8.0/cli/conf.d/20-iconv.ini:/etc/php/8.0/cli/conf.d/20-igbinary.ini:/etc/php/8.0/cli/conf.d/20-intl.ini:/etc/php/8.0/cli/conf.d/20-mbstring.ini:/etc/php/8.0/cli/conf.d/20-mysqli.ini:/etc/php/8.0/cli/conf.d/20-pdo_mysql.ini:/etc/php/8.0/cli/conf.d/20-pdo_sqlite.ini:/etc/php/8.0/cli/conf.d/20-phar.ini:/etc/php/8.0/cli/conf.d/20-posix.ini:/etc/php/8.0/cli/conf.d/20-readline.ini:/etc/php/8.0/cli/conf.d/20-redis.ini:/etc/php/8.0/cli/conf.d/20-shmop.ini:/etc/php/8.0/cli/conf.d/20-simplexml.ini:/etc/php/8.0/cli/conf.d/20-sockets.ini:/etc/php/8.0/cli/conf.d/20-sqlite3.ini:/etc/php/8.0/cli/conf.d/20-sysvmsg.ini:/etc/php/8.0/cli/conf.d/20-sysvsem.ini:/etc/php/8.0/cli/conf.d/20-sysvshm.ini:/etc/php/8.0/cli/conf.d/20-tokenizer.ini:/etc/php/8.0/cli/conf.d/20-xdebug.ini:/etc/php/8.0/cli/conf.d/20-xmlreader.ini:/etc/php/8.0/cli/conf.d/20-xmlwriter.ini:/etc/php/8.0/cli/conf.d/20-xsl.ini',
            'COMPOSER_BINARY' => '/usr/local/bin/composer',
            'IM_CONFIG_PHASE' => '1',
            'MANDATORY_PATH' => '/usr/share/gconf/ubuntu.mandatory.path',
            'LOGNAME' => 'chris',
            'JOURNAL_STREAM' => '9:76946',
            'DEFAULTS_PATH' => '/usr/share/gconf/ubuntu.default.path',
            'XDG_SESSION_CLASS' => 'user',
            'TERM' => 'xterm-256color',
            'GNOME_DESKTOP_SESSION_ID' => 'this-is-deprecated',
            'PATH' => '/var/www/personal/cakephp-rr/vendor/bin:/home/chris/.config/composer/vendor/bin/:/home/chris/.local/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin',
            'GDM_LANG' => 'en_US',
            'INVOCATION_ID' => '9cb74c2a95fc4751a4eb16579a8ca801',
            'SESSION_MANAGER' => 'local/chris-x1:@/tmp/.ICE-unix/3967,unix/chris-x1:/tmp/.ICE-unix/3967',
            'XDEBUG_HANDLER_SETTINGS' => '/tmp/AReEAw|1|*|*|/etc/php/8.0/cli/php.ini:/etc/php/8.0/cli/conf.d/10-mysqlnd.ini:/etc/php/8.0/cli/conf.d/10-opcache.ini:/etc/php/8.0/cli/conf.d/10-pdo.ini:/etc/php/8.0/cli/conf.d/15-xml.ini:/etc/php/8.0/cli/conf.d/20-calendar.ini:/etc/php/8.0/cli/conf.d/20-ctype.ini:/etc/php/8.0/cli/conf.d/20-curl.ini:/etc/php/8.0/cli/conf.d/20-dom.ini:/etc/php/8.0/cli/conf.d/20-exif.ini:/etc/php/8.0/cli/conf.d/20-ffi.ini:/etc/php/8.0/cli/conf.d/20-fileinfo.ini:/etc/php/8.0/cli/conf.d/20-ftp.ini:/etc/php/8.0/cli/conf.d/20-gettext.ini:/etc/php/8.0/cli/conf.d/20-iconv.ini:/etc/php/8.0/cli/conf.d/20-igbinary.ini:/etc/php/8.0/cli/conf.d/20-intl.ini:/etc/php/8.0/cli/conf.d/20-mbstring.ini:/etc/php/8.0/cli/conf.d/20-mysqli.ini:/etc/php/8.0/cli/conf.d/20-pdo_mysql.ini:/etc/php/8.0/cli/conf.d/20-pdo_sqlite.ini:/etc/php/8.0/cli/conf.d/20-phar.ini:/etc/php/8.0/cli/conf.d/20-posix.ini:/etc/php/8.0/cli/conf.d/20-readline.ini:/etc/php/8.0/cli/conf.d/20-redis.ini:/etc/php/8.0/cli/conf.d/20-shmop.ini:/etc/php/8.0/cli/conf.d/20-simplexml.ini:/etc/php/8.0/cli/conf.d/20-sockets.ini:/etc/php/8.0/cli/conf.d/20-sqlite3.ini:/etc/php/8.0/cli/conf.d/20-sysvmsg.ini:/etc/php/8.0/cli/conf.d/20-sysvsem.ini:/etc/php/8.0/cli/conf.d/20-sysvshm.ini:/etc/php/8.0/cli/conf.d/20-tokenizer.ini:/etc/php/8.0/cli/conf.d/20-xdebug.ini:/etc/php/8.0/cli/conf.d/20-xmlreader.ini:/etc/php/8.0/cli/conf.d/20-xmlwriter.ini:/etc/php/8.0/cli/conf.d/20-xsl.ini|3.1.2',
            'GNOME_TERMINAL_SCREEN' => '/org/gnome/Terminal/screen/8575e5d0_43e8_4ca2_8243_86897a6874fe',
            'XDG_MENU_PREFIX' => 'gnome-',
            'XDG_RUNTIME_DIR' => '/run/user/1000',
            'XDG_SESSION_PATH' => '/org/freedesktop/DisplayManager/Session0',
            'DISPLAY' => ':0',
            'LANG' => 'en_US.UTF-8',
            'XDG_CURRENT_DESKTOP' => 'ubuntu:GNOME',
            'GNOME_TERMINAL_SERVICE' => ':1.79',
            'LS_COLORS' => 'rs=0:di=01;34:ln=01;36:mh=00:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:mi=00:su=37;41:sg=30;43:ca=30;41:tw=30;42:ow=34;42:st=37;44:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arc=01;31:*.arj=01;31:*.taz=01;31:*.lha=01;31:*.lz4=01;31:*.lzh=01;31:*.lzma=01;31:*.tlz=01;31:*.txz=01;31:*.tzo=01;31:*.t7z=01;31:*.zip=01;31:*.z=01;31:*.dz=01;31:*.gz=01;31:*.lrz=01;31:*.lz=01;31:*.lzo=01;31:*.xz=01;31:*.zst=01;31:*.tzst=01;31:*.bz2=01;31:*.bz=01;31:*.tbz=01;31:*.tbz2=01;31:*.tz=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.war=01;31:*.ear=01;31:*.sar=01;31:*.rar=01;31:*.alz=01;31:*.ace=01;31:*.zoo=01;31:*.cpio=01;31:*.7z=01;31:*.rz=01;31:*.cab=01;31:*.wim=01;31:*.swm=01;31:*.dwm=01;31:*.esd=01;31:*.jpg=01;35:*.jpeg=01;35:*.mjpg=01;35:*.mjpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.svg=01;35:*.svgz=01;35:*.mng=01;35:*.pcx=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.m2v=01;35:*.mkv=01;35:*.webm=01;35:*.ogm=01;35:*.mp4=01;35:*.m4v=01;35:*.mp4v=01;35:*.vob=01;35:*.qt=01;35:*.nuv=01;35:*.wmv=01;35:*.asf=01;35:*.rm=01;35:*.rmvb=01;35:*.flc=01;35:*.avi=01;35:*.fli=01;35:*.flv=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.yuv=01;35:*.cgm=01;35:*.emf=01;35:*.ogv=01;35:*.ogx=01;35:*.aac=00;36:*.au=00;36:*.flac=00;36:*.m4a=00;36:*.mid=00;36:*.midi=00;36:*.mka=00;36:*.mp3=00;36:*.mpc=00;36:*.ogg=00;36:*.ra=00;36:*.wav=00;36:*.oga=00;36:*.opus=00;36:*.spx=00;36:*.xspf=00;36:',
            'XAUTHORITY' => '/home/chris/.Xauthority',
            'XDG_SESSION_DESKTOP' => 'ubuntu',
            'XMODIFIERS' => '@im=ibus',
            'SSH_AUTH_SOCK' => '/run/user/1000/keyring/ssh',
            'XDG_GREETER_DATA_DIR' => '/var/lib/lightdm-data/chris',
            'SHELL' => '/usr/bin/fish',
            'GDMSESSION' => 'ubuntu',
            'QT_ACCESSIBILITY' => '1',
            'GJS_DEBUG_OUTPUT' => 'stderr',
            'GPG_AGENT_INFO' => '/run/user/1000/gnupg/S.gpg-agent:0:1',
            'QT_IM_MODULE' => 'ibus',
            'PHP_BINARY' => '/usr/bin/php8.0',
            'PWD' => '/var/www/personal/cakephp-rr',
            'XDG_CONFIG_DIRS' => '/etc/xdg/xdg-ubuntu:/etc/xdg',
            'XDG_DATA_DIRS' => '/usr/share/ubuntu:/usr/local/share:/usr/share:/var/lib/snapd/desktop',
            'VTE_VERSION' => '6003',
            'RR_RELAY' => 'pipes',
            'RR_RPC' => 'tcp://127.0.0.1:6001',
            'RR_MODE' => 'http',
            'PHP_SELF' => 'cakephp-worker.php',
            'SCRIPT_NAME' => 'cakephp-worker.php',
            'SCRIPT_FILENAME' => 'cakephp-worker.php',
            'PATH_TRANSLATED' => 'cakephp-worker.php',
            'DOCUMENT_ROOT' => '',
            'REQUEST_TIME_FLOAT' => 1642567499.4602,
            'REQUEST_TIME' => 1642567499,
            'argv' => ['cakephp-worker.php'],
            'argc' => '1',
            'REQUEST_URI' => 'http://localhost:8080/index.json',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_METHOD' => 'GET',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:96.0) Gecko/20100101 Firefox/96.0',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_COOKIE' => 'Phpstorm-37de0ca2=ca4892d1-6b8b-4220-81a6-ad8b11dde23d; CookieAuth=%5B%22cnizzardini%22%2C%22%242y%2410%24UnrYDgq2SfjrQH.XNWBlc.JuneHeyRN%5C%2F6858e3DCMAa%5C%2FxWRYoxkf6%22%5D; PHPSESSID=llvucp06rquph426a6mi6qbnhn; csrfToken=7DhdIaeWFX60MWTVX5QWomYwZGNlMGE1ODk1OGQ1MTc1ZTQ5ODYxODNiMDM3YjUwZDA3MjExZjA%3D; Phpstorm-37de0ca4=34bada71-56a9-477c-9b26-e3268c411ad2',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.5',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_GPC' => '1',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
        ], $serverParams);
    }
}