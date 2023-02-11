Drupal.locale = { 'pluralFormula': function ($n) { return Number((((($n%10)==1)&&(($n%100)!=11))?(0):((((($n%10)>=2)&&(($n%10)<=4))&&((($n%100)<10)||(($n%100)>=20)))?(1):2))); }, 'strings': {"":{"An AJAX HTTP error occurred.":"Возникла AJAX HTTP ошибка.", "HTTP Result Code: !status":"Полученный код HTTP: !status", "An AJAX HTTP request terminated abnormally.":"HTTP запрос AJAX завершен неправильно.", "Debugging information follows.":"Следует отладочная информация.", "Path: !uri":"Путь: !uri", "StatusText: !statusText":"СтатусТекстом", "ResponseText: !responseText":"ОтветТекстом: !responseText", "ReadyState: !readyState":"ReadyState: !readyState", "Hide":"Скрыть", "Show":"Показать", "Show shortcuts":"Показать сочетания клавиш", "Hide shortcuts":"Скрыть ярлыки", "@title dialog":"Диалог @title", "Configure":"Настроить", "Loading":"Загрузка", "(active tab)":"(активная вкладка)", "Re-order rows by numerical weight instead of dragging.":"Упорядочить строки по весу вместо перетаскивания.", "Show row weights":"Показать вес строк", "Hide row weights":"Скрыть вес строк", "Drag to re-order":"Изменить порядок можно, перетащив пункт мышкой.", "Changes made in this table will not be saved until the form is submitted.":"Сделанные в списке изменения не вступят в силу, пока вы не сохраните их.", "Enabled":"Включено", "Close":"Закрыть", "Disabled":"Отключено", "Edit":"Редактировать", "Upload":"Закачать", "Done":"Готово", "Select all rows in this table":"Отметить все строки таблицы", "Deselect all rows in this table":"Снять отметку со всех колонок таблицы", "Not published":"Не опубликовано", "Please wait...":"Пожалуйста, подождите...", "Only files with the following extensions are allowed: %files-allowed.":"Прикреплять можно только файлы с расширениями: %files-allowed.", "By @name on @date":"@name, @date", "By @name":"@name", "Not in menu":"Не в меню", "Alias: @alias":"Синоним: @alias", "No alias":"Синоним не задан", "New revision":"Новая редакция", "The changes to these blocks will not be saved until the \u003Cem\u003ESave blocks\u003C\u002Fem\u003E button is clicked.":"Изменения, сделанные в блоках не вступят в силу пока вы не нажмете кнопку \u003Cem\u003EСохранить блоки\u003C\u002Fem\u003E.", "This permission is inherited from the authenticated user role.":"Это право наследуется от роли «Зарегистрированный пользователь».", "No revision":"Нет редакции", "Requires a title":"Требуется заголовок", "Not restricted":"Без ограничений", "Not customizable":"Не настраиваемый", "Restricted to certain pages":"Ограничено для определённых страниц", "The block cannot be placed in this region.":"Блок не может быть размещён в этом регионе.", "Customize dashboard":"Настроить панель управления", "Hide summary":"Скрыть анонс", "Edit summary":"Редактировать анонс", "Don\u0027t display post information":"Не показывать информацию материала", "The selected file %filename cannot be uploaded. Only files with the following extensions are allowed: %extensions.":"Выбранный файл %filename не может быть загружен. Возможно загрузка файлов только со следующими расширениями: %extensions.", "Autocomplete popup":"Всплывающее автозаполнение", "Searching for matches...":"Поиск совпадений...", "Log messages":"Сообщения в лог", "Please select a file.":"Пожалуйста выберите файл.", "You are not allowed to operate on more than %num files.":"Вам не разрешено управлять больше чем с %num файлами.", "Please specify dimensions within the allowed range that is from 1x1 to @dimensions.":"Пожалуйста укажите размер внутри разрешённого диапозона от 1x1 до @dimensions.", "%filename is not an image.":"Файл %filename не является изображением.", "File browsing is disabled in directory %dir.":"Просмотр файлов запрещен для папки %dir.", "Do you want to refresh the current directory?":"Вы хотите обновить текущую папку?", "Delete selected files?":"Удалить выбранные файлы?", "Please select a thumbnail.":"Выберите миниатюру.", "You must select at least %num files.":"Необходимо выбрать не менее %num файлов.", "You can not perform this operation.":"Вы не можете выполнить эту операцию.", "Insert file":"Вставить файл", "Change view":"Сменить вид", "Automatic alias":"Автоматический синоним", "Available tokens":"Доступные маркеры", "Insert this token into your form":"Вставить этот токен в вашу форму", "First click a text field to insert your tokens into.":"Сначала кликните в текстовое поле, чтобы вставитьтокены.", "Loading token browser...":"Загрузка браузера меток...", "Remove group":"Удалить условие", "Apply (all displays)":"Применить (все отображения)", "Apply (this display)":"Применить (это отображение)", "Revert to default":"Вернуть к настройкам по умолчанию"}} };