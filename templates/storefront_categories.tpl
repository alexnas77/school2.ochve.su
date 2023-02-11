<div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>Выберите класс и дату</b></div>
<ul>
  {foreach name=mainbody item=category from=$Categories}
    <li style="margin: 5px 0px;">
      <A HREF='catalog/{$category->category_id}'>{$category->name}</A>
    </li>
  {/foreach}
</ul>