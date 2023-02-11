<?php
class Language
{

#General messages
var $HOME_TEXT = 'На главную';
var $EMPTY_LIST = 'Пусто';
var $EDIT = 'Редактировать';
var $MOVE_UP = 'Вверх';
var $MOVE_DOWN = 'Вниз';
var $DELETE_SELECTED = 'Удалить выбранные';
var $SAVE = 'Сохранить';
var $ARE_YOU_SURE_TO_DELETE = 'Подтвердите удаление';


#MainPage
var $NEWS = 'Новости';
var $ARTICLES = 'Статьи';
var $GALLERY = 'Фотогалерея';
var $POLLS = 'Опросы';
var $SETTINGS = 'Настройки';
var $CONTACTS = 'Контакты';
var $PRODUCTS_CATEGORIES = 'Классы';
var $PRODUCTS = 'Ученики';
var $PROPERTIES = 'Свойства';
var $PROPERTIES_CATEGORIES = 'Группы Свойств';
var $INDEX_SEARCH = 'Расширенный Поиск';
var $ORDERS = 'Заказы';
var $PRICELIST_IMPORT = 'Импорт прайса';
var $PRICELIST_EXPORT = 'Экспорт прайса';
var $PRODUCTS_IMPORT = 'Импорт товаров';
var $CURRENCIES = 'Валюты';
var $PAYMENT = 'Оплата';
var $STATISTICS = 'Статистика';
var $USERS = 'Пользователи';
var $USERS_CATEGORIES = 'Категории пользователей';
var $USERCOMMENTS = 'Отзывы';
var $FAQ = 'Вопросы и Ответы';
var $ANNOUNCEMENT = 'Объявления';
var $GOOGLE_SITEMAP = 'Google Sitemap';
var $LINKS = 'Каталог ссылок';
var $OUR_LINKS = 'Наши ссылки';
var $LINKS_CATEGORIES = 'Разделы ссылок';


#PageNavigation
var $PAGES = 'Страницы';

#Faq
var $FNAME = 'Имя пользователя';
var $CITY = 'Город';
var $MAIL = 'Эл. почта';
var $MESSAGE = 'Сообщение';
//var $ANSWER = 'Ответ';
var $FSHOW = 'Показать на сайте';
var $ENTER_ANSWER = 'Введите ответ';
var $NEW_FAQ = 'Новый вопрос - ответ';
var $SEND = 'Отправить ответ пользователю';

#Announcement
var $SALE_TYPE = 'Тип объявления';
//var $CATEGORY = 'Категория';
var $EXPIRES = 'Срок истечения';
//var $DATE = 'Дата';
//var $BRAND = 'Производитель';
//var $MODEL = 'Модель';
var $YEAR = 'Год';
var $VOLUME = 'Объем';
//var $CITY = 'Город';
//var $MAIL = 'Эл. почта';
//var $PHONE = 'Телефон';
var $CONTACT = 'Контактное лицо';
//var $FSHOW = 'Показать на сайте';
//var $SEND = 'Отправить ответ пользователю';

#Gallery
var $EDIT_FOTO = 'Редактировать фото';
var $NEW_FOTO = 'Новое фото';

#Contacts
var $EDIT_CONTACT = 'Редактировать контакт';
var $NEW_CONTACT = 'Новый контакт';
var $NUMBER_ADRESS = 'Номер/адрес';
var $MODE = 'Режим';

#Sections
var $NEW_SECTION = 'Новый раздел';
var $NAME = 'Название';
var $SECTION_TYPE = 'Тип раздела';
var $PAGE_URL = 'URL страницы';
var $ENTER_SECTION_NAME = 'Введите название';
var $ENTER_PAGE_URL = 'Введите URL страницы';
var $EDIT_SECTION = 'Редактирование раздела';
var $TITLE = 'Заголовок';
var $DESCRIPTION = 'Описание';
var $KEYWORDS = 'Keywords';
var $PAGE_TEXT = 'Текст страницы';
var $TARGET_URL = 'Целевой URL';

#Mapsite

var $IN_MAP = 'В карте сайта';
var $ALTURL = 'Другой URL';
var $EXT = 'Расширение';
var $MAPSITE = 'Карта сайта';

var $SECTION_WITH_SAME_URL_ALREADY_EXISTS = 'Раздел с таким URL существует. Выберите другой URL.';

#News
var $NEW_NEWS_ITEM = 'Добавить новость';
var $EDIT_NEWS_ITEM = 'Редактирование новости';
var $DATE = 'Дата';
var $ANNOTATION = 'Аннотация';
var $NEWS_TEXT = 'Текст';
var $ENTER_TITLE = 'Введите заголовок';
var $ENTER_CORRECT_DATE = 'Введите правильную дату';
var $SHOW_MAIN = 'Показывать на главной';

#Articles
var $NEW_ARTICLE = 'Новая статья';
var $EDIT_ARTICLE = 'Редактирование статьи';
var $ARTICLE_TEXT = 'Текст статьи';
var $MAIN = 'Показывать на главной';

#Index_properties
//var $CATEGORIES = 'Категории';
var $SUBCATEGORIES = 'Подкатегории';
var $NEW_PROPERTY = 'Новое свойство(а)';
var $EDIT_PROPERTY = 'Редактирование свойства';

//var $NAME = 'Название';
var $LABEL = 'Метка';
var $TYPE = 'Тип';
var $VALUE = 'Значение';
var $DEFAULT = 'По умолчанию';
var $SHOW = 'Показать в кратком описании';
//var $ALL = 'Все';
//var $ACTIVE = 'Активен';
//var $DELETE = 'Удалить';
//var $SAVE_CHANGES = 'Сохранить изменения';

//var $BACK = 'Назад';

//var $ENTER_NAME = 'Введите название';
var $ENTER_LABEL = 'Введите метку';
var $SEARCH = 'Поиск';
var $SEARCH_TYPE = 'Режим Поиска';

#Links
var $URL = 'Адрес ссылки';
var $NEW_LINK = 'Новая ссылка';
var $EDIT_LINK = 'Редактировать ссылку';
var $USE_BACK = 'Использовать как обратную';
var $BACK_LINK = 'Обратная ссылка';
var $PATH = 'Путь';
var $ENTER_PATH = 'Введите путь';
var $ENTER_OTHER_PATH = 'Введите другой путь';

#Storefront
var $CATEGORIES = 'Классы';
var $BRANDS = 'Производители';
var $NEW_PRODUCT = 'Новый ученик';
var $EDIT_PRODUCT = 'Редактирование ученика';

var $CATEGORY = 'Класс';
var $BRAND = 'Производитель';
var $ALL = 'Все';
var $MODEL = 'Фамилия И.О.';
var $MODEL_CODE = 'Номер товара';
var $HIT = 'Хит';
var $PRICE = 'Цена';
var $CURRENCY = 'Валюта';
var $GUARANTEE = 'Гарантия, лет';
var $DELIVERY = 'Срок поставки, дней';
var $IN_STOCK = 'На складе';
var $ACTIVE = 'Активен';
var $USED = 'Бывший в употреблении';
var $DEFECTS = 'Список дефектов';
var $DELETE = 'Удалить';
var $SAVE_CHANGES = 'Сохранить изменения';

var $SHORT_DESCRIPTION = 'Краткое описание';
var $FULL_DESCRIPTION = 'Полное описание';
var $PHOTO = 'Фото';
var $BACK = 'Назад';

var $ENTER_BRAND = 'Введите производителя';
var $ENTER_MODEL = 'Введите модель';

var $FILE_UPLOAD_ERROR = 'Ошибка загрузки файла';

#Storefront categories
var $CAT_ENABLED = 'Активна';
var $CAT_DISABLED = 'Неактивна';
var $NEW_CATEGORY = 'Новая категория';
var $EDIT_CATEGORY = 'Редактирование категории';
var $PARENT_CATEGORY = 'Родительская категория';
var $ROOT_CATEGORY = 'Корень';
var $ENTER_NAME = 'Введите название';
var $TOPTEXT = 'Верхний текст';
var $ALIES = 'Синонимы';
//var $TITLE = 'Название';

#Users
var $LOGIN = 'Логин';
var $USER_CATEGORY = 'Категория';
var $USERNAME = 'Имя';
var $ADDRESS = 'Адрес';
var $PHONE = 'Телефон';
var $ORDERS_HISTORY = 'История заказов';
var $UNDEFINED_CATEGORY = 'Не определена';

var $EDIT_USER = 'Редактирование пользователя';
var $PASSWORD = 'Пароль';
var $USER_ENABLED = 'Активен';
var $USER_ENABLE = 'Разблокировать';
var $USER_DISABLE = 'Заблокировать';
var $APPLY_FILTER = 'Применить фильтр';

#Users categories
var $DISCOUNT = 'Скидка';
var $NEW_USERS_CATEGORY = 'Новая категория';
var $EDIT_USERS_CATEGORY = 'Редактирование категории';
var $PRICELIST = 'Прайслист';
var $ISADMIN = 'Доступ в Админку';
var $ISADMIN_HELP = 'Разрешает доступ в Административную часть сайта';
var $SETTINGS_HELP = 'Управление Настройками сайта';
var $PRODUCTS_HELP = 'Доступ к модулям ';

#Orders

var $COMMENT = 'Комментарий';
var $COMMENT2 = 'Удобное для Вас время';
var $STATUS = 'Статус';
var $PCS = 'шт.';
var $IS_IN_STOCK = 'на складе';
var $NOT_IN_STOCK = 'нет на складе';
var $CHANGE_STATUS = 'Изменить статус';
var $NEW_STATUS = 'Новый';
var $ACCEPTED_STATUS = 'Принят';
var $DONE_STATUS = 'Выполнен';
var $PAY = 'Способ оплаты';
var $PAY2 = 'Как Вы узнали о компании "Ягуар"';
var $PAID = 'Оплачено';
var $BILL = 'Счёт';
var $BILL2 = 'Выставочный зал';
var $SHOW_BILL = 'Показать Счёт';
//var $CITY = 'Город';
var $METRO = 'Станция метро';
var $ADDRESS2 = 'Улица';

#Statistics

var $LAST_YEAR = 'Последний год';
var $LAST_MONTH = 'Последний месяц';
var $LAST_WEEK = 'Неделя';
var $YESTERDAY = 'Вчера';
var $TODAY = 'Сегодня';
var $CLEAR_STATISTICS = 'Очистить статистику';

var $OLDER_THAN_YEAR = 'Старше года';
var $OLDER_THAN_MONTH = 'Старше месяца';
var $OLDER_THAN_WEEK = 'Старше недели';
var $OLDER_THAN_DAY = 'Старше дня';
var $CLEAR_ALL = 'Всю';
var $STATISTICS_FOR = 'Статистика для ';
var $PRODUCT = 'Товар';
var $GO_TO_MAIN_PAGE = 'На главную';

#Comments
var $USERCOMMENT = 'Отзыв';
var $POINTS = 'Оценка';

#Google sitemap
var $GENERATE = 'Сгенерировать автоматически';

#Pricelist import
var $UPDATE_PRICES = 'Обновить цены';
var $PRICELIST_IMPORT_HELP = 'Скопируйте из файла Excel столбцы с производителем, моделью и ценой,  и вставьте в форму ниже';
var $IMPORT_RESULTS = 'Результаты';
var $OLD_PRICE = 'Старая цена';
var $NEW_PRICE = 'Новая цена';
var $NO_PRICES_IMPORTED = 'Ни один товар не найден. Пожалуйста, проверьте прайслист';
var $UPDATED = 'Обновлен';
var $PRODUCT_NOT_FOUND = 'Товар не найден';

#Pricelist export
var $PRICELIST_EXPORT_IN_VARIOUS_FORMATS = 'Экспорт прайса в различные форматы';
var $FORMAT = 'Формат';
var $PRICELIST_URL = 'URL прайса';
var $DOWNLOAD = 'Скачать';

#Products import
var $IMPORT_FROM = 'Импорт из';
var $IMPORT_SHOPSCRIPT_HELP = 'Для импорта товаров из Shop Script зайдите в старом магазине в раздел Каталог -> <nobr>Экспорт товаров в CSV</nobr> и создайте CVS-файл. После импорта скопируйте фотографии товаров в папку foto/storefront/.';
var $PRODUCTS_FILE = 'Файл товаров';
var $START_IMPORT = 'Начать импорт';
var $PRODUCTS_IMPORT_RESULTS = 'Результаты';
var $PRODUCT_ADDED = 'Добавлен';
var $PRODUCT_EXISTS = 'Баян';

#Currencies
var $MAIN_CURRENCY = 'Основная';
var $SIGN = 'Знак';
var $RATE = 'Курс';
var $CODE = 'Код ISO 3';
var $ADD_CURRENCY = 'Добавить валюту';

#Settings
var $SITE_NAME = 'Имя сайта';
var $SITE_NAME_HELP = '';
var $COMPANY_NAME = 'Имя компании';
var $COMPANY_NAME_HELP = 'Используется в экспорте прайса в формате Yandex XML';
var $COMPANY_NAME_HELP_PAY = 'Имя компании {$Settings->company_name}';
var $ADMIN_EMAIL = 'Email администратора';
var $ADMIN_EMAIL_HELP = 'На него приходят заказы';
var $MAIN_PAGE = 'Главная страница';
var $MAIN_PAGE_HELP = '';
var $META_TITLE = 'Meta title';
var $META_TITLE_HELP = 'Meta title для главной страницы';
var $META_KEYWORDS = 'Meta keywords';
var $META_KEYWORDS_HELP = 'Meta keywords для главной страницы';
var $META_DESCRIPTION = 'Meta description';
var $META_DESCRIPTION_HELP = 'Meta description для главной страницы';
var $PHONES = 'Телефоны';
var $PHONES_HELP = '';
var $PHONES_HELP_PAY = 'Телефоны {$Settings->phones}';
var $COUNTERS_CODE = 'Коды счетчиков';
var $COUNTERS_CODE_HELP = 'Вставляйте сюда коды счетчиков';
var $FOOTER_TEXT = 'Нижний текст';
var $FOOTER_TEXT_HELP = 'Копирайт';

#Payment
var $WM_SHOP_PURSE_WMR = 'Кошелёк WMR';
var $WM_SHOP_PURSE_WMR_HELP = 'Номер кошелька WMR (рубли)';
var $WM_SHOP_PURSE_WMZ = 'Кошелёк WMZ';
var $WM_SHOP_PURSE_WMZ_HELP = 'Номер кошелька WMZ (доллары)';
var $WM_SHOP_PURSE_WME = 'Кошелёк WME';
var $WM_SHOP_PURSE_WME_HELP = 'Номер кошелька WME (евро)';
var $WM_SHOP_WMID = 'WMID';
var $WM_SHOP_WMID_HELP = 'Номер пользователя в системе WebMoney (WebMoney ID)';
var $LMI_SECRET_KEY = 'Секретный ключ';
var $LMI_SECRET_KEY_HELP = 'Секретный ключ для проверки подлинности платежа';
var $BILL_TEMPLATE = 'Шаблон Счёта';
var $COMPANY_ADDRES = 'Адрес компании';
var $COMPANY_ADDRES_HELP_PAY = 'Адрес компании {$Settings->company_addres}';
var $INN = 'ИНН';
var $INN_HELP_PAY = 'ИНН {$Settings->inn}';
var $KPP = 'КПП';
var $KPP_HELP_PAY = 'КПП {$Settings->kpp}';
var $RESIVER = 'Получатель';
var $RESIVER_HELP_PAY = 'Получатель {$Settings->resiver}';
var $RESIVER_BANK = 'Банк получателя';
var $RESIVER_BANK_HELP_PAY = 'Банк получателя {$Settings->resiver_bank}';
var $RESIVER_ACCOUNT = 'Расчетный счет';
var $RESIVER_ACCOUNT_HELP_PAY = 'Расчетный счет {$Settings->resiver_account}';
var $RESIVER_BANK_ACCOUNT = 'Корреспондентский счет';
var $RESIVER_BANK_ACCOUNT_HELP_PAY = 'Корреспондентский счет {$Settings->resiver_bank_account}';
var $BIK = 'БИК банка получателя';
var $BIK_HELP_PAY = 'БИК банка получателя {$Settings->bik}';
var $NDS = 'НДС';
var $NDS_HELP_PAY = 'НДС,% {$Settings->nds}';
var $PAYER = 'Плательщик';
var $PAYER_HELP_PAY = 'Плательщик {$Payer}';
var $ACCEPT = 'Грузополучатель';
var $ACCEPT_HELP_PAY = 'Грузополучатель {$Accept}';
var $DIRECTOR = 'Руководитель предприятия';
var $DIRECTOR_HELP_PAY = 'Директор {$Settings->director}';
var $GLAVBUH = 'Главный бухгалтер';
var $GLAVBUH_HELP_PAY = 'Главный бухгалтер {$Settings->glavbuh}';
var $FAX = 'Факс';
var $FAX_HELP_PAY = 'Факс {$Settings->fax}';
var $BILL_EXP = 'Срок действия счета';
var $BILL_EXP_HELP_PAY = 'Срок действия счета в днях {$Settings->bill_exp}';
var $LINK_EMAIL_TEXT = 'E-mail каталога ссылок';
var $LINK_EMAIL_TEXT_HELP = 'E-mail уведомления о добавлении каталога ссылок';
#Polls
var $POLL = 'Опрос';
var $VOTES_NUMBER = 'Число голосов';
var $NEW_POLL = 'Новый опрос';
var $QUESTION = 'Вопрос';
var $ANSWER = 'Ответ';
var $NEW_ANSWER = 'Добавить вариант ответа';
var $VOTE = 'Ответ';

}

?>