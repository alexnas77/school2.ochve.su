<H1>Фотографии {$Product->model} {$Product->brand}</H1>
<div align="center">
<table>
{foreach item=foto from=$Fotos}
  <tr>
    <td>
      <img width="500px" src='foto/storefront/{$foto->filename}'></img>
    </td>
  </tr>
{/foreach}
</table>
</div>