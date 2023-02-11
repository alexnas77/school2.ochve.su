<H1>Корзина</H1>
{$Baskettext}
<br />
{if $Pay==3}
<div style="width:90%;margin:5px 5%;">
<div id="show" style="width:100%; height:auto; margin:10px 10px; padding:10px; background-color:White; overflow-y:visible;">
{eval var=$HTML}
</div>
<br /><br /><a href="?section=7&download=1&order_id={$Order_id}">Сохранить Счет</a>
</div>
{/if}
