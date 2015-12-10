<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="expires" content="Sun, 01 Jan 2113 07:01:00 GMT">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Jam</title>
    <meta name="keywords" content="Jam, сайт-визитка, заказать сайт визитку, визитка, качество, быстро, джем, rajam, риа джем, не дорого, бесплатная консультация">
    <meta name="description" content="Создание сайта визитки. Мариуполь, Киев, Донецк.">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link href="favicon.jpg" rel="icon" type="image/jpeg">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?
require_once("PHPClasses/TEmail.php");
function output_err($err) {
    $ERROR[0] = "Введите имя!";
    $ERROR[1] = "Неверное указан email!";
    $ERROR[2] = "Неверно указан телефон!";
    $ERROR[3] = "Заявка не отправлена, попробуйте, пожалуйста, еще раз!";
    $ERROR[4] = "Введите сообщение дизайнеру!";

    echo "<div class='message'>".$ERROR[$err]."</div>";
}
if (isset($_POST["submit"])) 
{
    /**
    *   Заявка на сайт
    */
    $_POST['email'] =  substr(htmlspecialchars(trim($_POST['email'])), 0, 50);
    $_POST['name'] =  substr(htmlspecialchars(trim($_POST['name'])), 0, 30); 
    $_POST['phone'] =  substr(htmlspecialchars(trim($_POST['phone'])), 0, 30); 
    $error = false;
    // если не заполнено поле "Имя" - показываем ошибку 0 
    if (empty($_POST['name'])) 
    {
        $error = true; 
        output_err(0); 
    }  
    // если неправильно заполнено поле email - показываем ошибку 1 
    if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email']))
    {
        $error = true; 
        output_err(1); 
    }  
  // если не заполнено поле "Телефон" - показываем ошибку 2 
    if(empty($_POST['phone'])) 
    {
        $error = true; 
        output_err(2); 
    }  
    if (!$error)
    {        
        $email=new TEmail;
        $email->from_email='webmaster@rajam.com.ua';
        $email->from_name="Jam Vizitka";
        $email->to_email='pruv@rarus.kiev.ua';
        $email->to_name='Jam';
        $email->subject='Клиент';
        $email->body="Клиент ".$_POST["name"]."\r\nEmail: ".$_POST["email"]."\r\nТелефон: ".$_POST["phone"]."\r\n";
        
        if ($email->send())
        {
            header("Location: http://".$_SERVER["SERVER_NAME"]."/?ver=OK");
        } else {
            output_err(3);
            $email->body = "error send:\t".$email->body;
        }
        file_put_contents("../../logs/vizitka_users_log.txt", "\n".date("m.d.y H:i:s")."\n".$email->body."\n".str_repeat("-", 40)."\n", FILE_APPEND | LOCK_EX);
    }
}  
else if (isset($_POST["submit-to-designer"]))
{
    /**
    *   Сообщение дизайнеру
    */
    $error = false;
    $_POST['email'] =  substr(htmlspecialchars(trim($_POST['email'])), 0, 50);
    $_POST['message'] =  substr(htmlspecialchars(trim($_POST['message'])), 0, 10000);
    if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email']))
    {
        $error = true; 
        output_err(1); 
    } 
    if(empty($_POST['message'])) 
    {
        $error = true; 
        output_err(4); 
    }
    if (!$error)
    {        
        $email=new TEmail;
        $email->from_email='webmaster@rajam.com.ua';
        $email->from_name="Jam Vizitka";
        $email->to_email='pruv@rarus.kiev.ua';
        $email->to_name='Jam';
        $email->subject='Клиент желающий бесплатных консультаций';
        $email->body="Email: ".$_POST["email"]."\r\nСообщение: ".$_POST["message"]."\r\n";
        if ($email->send())
        {
            header("Location: http://".$_SERVER["SERVER_NAME"]."/?ver=OK");
        } else {
            output_err(3);
            $email->body = "error send:\t".$email->body;
        }
        file_put_contents("../../logs/vizitka_users_log.txt", "\n".date("m.d.y H:i:s")." to designer\n".$email->body."\n".str_repeat("-", 40)."\n", FILE_APPEND | LOCK_EX);
    }

} else if (isset($_GET["ver"]) && $_GET["ver"] == "OK") 
{
    echo "<div class='message success'>Ваша заявка успешно принята!</div>";
}
?>
    <div class="modal-wrapper">
        <div class="modal-window">
            <div class="title-window">
                Письмо дизайнеру<span class="close-window"></span>
            </div>
            <form class="email-to-designer" method="post">
                <label for="email">Ваш email:</label><input id="email" name=
                "email" required="" type="email"> <label for="message">Ваш
                вопрос дизайнеру:</label> 
                <textarea cols="60" id="message" name="message" required=""
                rows="5">
</textarea> <input name="submit-to-designer" type="submit" value="ОТПРАВИТЬ">
                </form>
        </div>
    </div>
    <header class="header">
        <a href="http://rajam.com.ua" class="logo"><!--div class="logo-sign"><b>Рекламно-творческая лаборатория</b></div--></a>
         <div class="center-wrapper"><div class="header-sign"><b>Разработка сайтов любого уровня сложности</b></div></div>
        <div class="right-panel">
            <div class="phone">
                <div class="logo-phone"></div>
                <div class="phone-container">
                    <span>050 195-83-55</span> <small>На связи с 10.00 до
                    17.00</small>
                </div>
            </div>
        </div>
       <div class="border"></div>
    </header>
    <div class="clearfix"></div>
    <div class="page-first">
        <div class="advantages"><b>минимум затрат - максимум прибыли</b></div>
        <!--ul class="list-advantages">
            <li><b>быстрый</b></li>
            <li><b>недорогой</b></li>
            <li><b>стабильный</b></li>
        </ul-->
        <h1 class="title"><strong>САЙТ-ВИЗИТКА</strong></h1>
        <div class="under-title">
            ВАШЕ ПРЕДСТАВИТЕЛЬСТВО В СЕТИ ИНТЕРНЕТ
        </div>
        <div class="container">
            <ul class="list-offers">
                <!--li>Отрисовка дизайна за неделю</li>
                <li>Стабильная работа сайта на платформе 1С Битрикс</li>
                <li>Простое управление сайтом</li>
                <li>Поддержка после создания</li-->
                <li>Стабильная работа сайта на платформе 1С Битрикс</li>
                <li>Практичный и понятный интерфейс управления</li>
                <li>Возможность последующего развития сайта до Корп.портала и Интернет-магазина</li>
            </ul>
            <form class="form-request" method="post">
                <div class="before-date">
                    До 15 декабря
                </div>
                <div class="form-sign">
                    <span class="sign-request">ОСТАВЬТЕ ЗАЯВКУ</span> на
                    разработку сайта визитки
                </div>
                <div class="free-consultation">
                    и бесплатно обсудите с дизайнером оптимальный вид Вашего
                    сайта.
                </div><input name="name" placeholder="Как к Вам обращаться?"
                type="text"><br>
                <input name="phone" placeholder="Ваш номер телефона" type=
                "text"><br>
                <input name="email" placeholder="Ваш email" type=
                "email"><br>
                <input name="submit" type="submit" value="ХОЧУ САЙТ-ВИЗИТКУ!">
            </form>
        </div>
    </div>
    <div class="not-templating">
        <div class="btn-next-frame-wrapper"></div>
        <div class="btn-next-frame">
            <div class="sign-not-templating">
                А можно сделать не шаблонно?
            </div>
            <div class="do-look">
                Давайте посмотрим
            </div>
            <div class="logo-arrow-down"></div>
        </div>
        <div class="btn-wrapper one"></div>
        <div class="btn-wrapper two"></div>
    </div>
    <div class="page-second">
        <div class="wrapper">
            <div class="title">
                <h5>НЕСТАНДАРТНАЯ АРХИТЕКТУРА САЙТА:</h5>
            </div>
            <div class="image-container">
                <div class="frame image1"><img alt="image" src=
                "images/image1_2-frame2.png"></div>
                <div class="frame image2"><img alt="image" src=
                "images/image2-frame2.png"></div>
                <div class="frame image3"><img alt="image" src=
                "images/image3-frame2.png"></div>
                <div class="frame image4"><img alt="image" src=
                "images/image4-frame2.png"></div>
                <div class="frame image5"><img alt="image" src=
                "images/image5-frame2.png"> <img alt="" class="square" src=
                "images/image5_2-frame2.png"></div>
            </div>
            <div class="under-sign">
                <p class="description">Оформить портфолио, обыграть
                преимущества, выгодно подчеркнуть достоинства, выйти за рамки.
                Стиль очень броский, с ним нужно быть осторожным. <span class=
                "sign">но ведь можно<span class=
                "arrow-right"></span></span></p>
                <div class="btn-designer-consult">
                    ПРОКОНСУЛЬТИРОВАТЬСЯ С ДИЗАЙНЕРОМ
                </div>
                <div class="free-sign">
                    Бесплатно до 15 декабря!
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="page-third">
        <div class="wrapper">
            <p class="description">Упор на атмосферные фото используется, если
            необходимо продемонстрировать настроение от товара или услуги. Как
            раз тот случай, когда лучше раз увидеть, чем пять раз
            прочитать:</p>
            <h5 class="title">ЭМОЦИОНАЛЬНАЯ</h5><em class=
            "under-title">архитектура сайта</em>
            <div class="sign">
                Чтобы не ошибиться в выборе, <span class=
                "recommended">рекомендуем<span class=
                "arrow-down"></span></span>
            </div>
            <div class="btn-designer-consult">
                ПРОКОНСУЛЬТИРОВАТЬСЯ С ДИЗАЙНЕРОМ
            </div>
            <div class="free-sign">
                Бесплатно до 15 декабря!
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="page-fourth">
        <div class="wrapper">
            <div class="left-top">
                КОНЕЧНО,
            </div>
            <div class="mid-top">
                <h2>Flat-дизайн</h2>
            </div>
            <div class="right-top">
                <p>одно из самых трендовых и обсуждаемых направлений в
                дизайне</p>
            </div>
            <div class="right-container">
                <div class="right-mid"><img alt="Responsive design" src=
                "images/Telephones.svg"></div>
                <div class="right-bottom"></div>
            </div>
            <div class="left-container">
                <div class="left-mid">
                    <p>используется для разных<span class=
                    "first">НАПРАВЛЕНИЙ</span><span class=
                    "second">деятельности</span></p>
                </div>
                <div class="left-bottom">
                    Но его обманчивая простота тоже требует разумного подхода
                </div>
            </div>
            <div class="btn-container">
                <div class="arrow-container">
                    <div class="arrow-down"></div>
                </div>
                <div class="sign">
                    поэтому не будет лишним
                </div>
                <div class="btn-designer-consult">
                    ПРОКОНСУЛЬТИРОВАТЬСЯ С ДИЗАЙНЕРОМ
                </div>
                <div class="free-sign">
                    Бесплатно до 15 декабря
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="page-fifth">
        <div class="wrapper">
            <div class="right-image"></div>
            <div class="left-image"></div>
            <div class="obtekanie">
                <p class="fifth-page-p"><span class=
                "fifth-page-span1">ЭСКИЗНЫЙ</span> <span class=
                "fifth-page-span2">ВАРИАНТ</span></p>
                <div class="float-container floater28"></div>
                <div class="float-right-container floater-right34"></div>
                <div class="float-container floater30"></div>
                <div class="float-right-container floater-right39"></div>
                <div class="float-container floater28"></div>
                <div class="float-right-container floater-right40"></div>
                <div class="float-container floater28"></div>
                <div class="float-right-container floater-right42"></div>
                <div class="float-container floater26"></div>
                <div class="float-right-container floater-right40"></div>
                <div class="float-container floater25"></div>
                <div class="float-right-container floater-right50"></div>
                <div class="float-container floater27"></div>
                <div class="float-right-container floater-right50"></div>
                <p class="text-container">Сайт полностью отрисовывается от
                руки, - если хочется чего-то самобытного и действительно
                индивидуального.</p>
                <div class="fifth-page-border"></div>
                <div class="wrapper-p3"></div>
                <p class="fifth-page-p3">ВОЗМОЖНО, ВЫ EЩЁ<br>
                СОМНЕВАЕТЕСЬ?</p>
                <p class="fifth-page-p4"><span class="fifth-page-span3">мы
                поможем</span><br>
                <span class="fifth-page-span4">определиться!</span></p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="footer-form">
        <div class="container">
            <form class="form-request" method="post">
                <div class="before-date">
                    До 15 декабря
                </div>
                <div class="form-sign">
                    <span class="sign-request"><b>ОСТАВЬТЕ ЗАЯВКУ</b></span> на
                    разработку сайта визитки
                </div>
                <div class="free-consultation">
                    и бесплатно обсудите с дизайнером оптимальный вид Вашего
                    сайта.
                </div><input name="name" placeholder="Как к Вам обращаться?"
                type="text"><br>
                <input name="phone" placeholder="Ваш номер телефона" type=
                "text"><br>
                <input name="email" placeholder="Ваш email" type=
                "email"><br>
                <input name="submit" type="submit" value="ХОЧУ САЙТ-ВИЗИТКУ!">
            </form>
            <ul class="list-offers">
                <li>Стабильная работа сайта на платформе 1С Битрикс</li>
                <li>Практичный и понятный интерфейс управления</li>
                <li>Возможность последующего развития сайта до Корп.портала и Интернет-магазина</li>
            </ul>
        </div>
    </div>
    <footer class="footer">
        <div class="wrapper">
            <div class="left-content">
                <b>design@rajam.com.ua</b><br />
                +38 (050) 253-91-71<br />
                +38 (050) 195-83-55<br />
                +38 (093) 708-00-13<br />
                (на связи с 10.00 до 17.00)<br />
                <!--ul class="social-list">
                    <li>
                        <a class="vk" href="https://vk.com/id254863592" target="_blank"></a>
                    </li>
                    <li>
                        <a class="fb" href="https://www.facebook.com/rajam.com.ua/?pnref=lhc" target="_blank"></a>
                    </li>
                </ul-->
            </div>
            <div class="right-content">
                <h3 class="footer-title">РЕКЛАМНО-ТВОРЧЕСКАЯ ЛАБОРАТОРИЯ</h3>
                <span class="some-text">Разработка сайтов любого уровня сложности</span>
            </div>
        </div>
 <!-- Yandex.Metrika informer -->
 <div class="yandex-block">
    <a href="https://metrika.yandex.ru/stat/?id=34087105&amp;from=informer"
target="_blank" rel="nofollow"><img src="//informer.yandex.ru/informer/34087105/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:34087105,lang:'ru'});return false}catch(e){}"/></a>
</div>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter34087105 = new Ya.Metrika({id:34087105,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/34087105" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    </footer>
    <script src=
    "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
    </script> 
    <script src="js/script.js">
    </script>
    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
    (function(){ var widget_id = 'nnRL7vdC4F';
    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
    <!-- {/literal} END JIVOSITE CODE -->
</body>
</html>