<!DOCTYPE html>
<html lang="en">
<head>
    <style>
            body {
        padding: 14px;
        text-align: center;
        }

        table {
        width: 100%;
        margin: 20px auto;
        table-layout: auto;
        }

        .fixed {
        table-layout: fixed;
        }

        table,
        td,
        th {
        border-collapse: collapse;
        }

        th,
        td {
        padding: 10px;
        border: solid 1px;
        text-align: center;
        }

        .w {
        width: 400px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h2>Using the <code>table-layout</code> property</h2>

<table id="t">
  <colgroup>
    <col class="w">
    <col>
    <col>
    <col>
  </colgroup>
  <thead>
    <tr>
      <th>Example text</th>
      <th>Example text</th>
      <th>Here is a longer piece of text</th>
      <th>Example text</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>A much longer piece of content for demo purposes. More goes here.</td>
      <td>Example text</td>
      <td>Example text</td>
      <td>Example text</td>
    </tr>
    <tr>
      <td>Example text</td>
      <td>Example text</td>
      <td>Example text</td>
      <td>Example text</td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td>Example text</td>
      <td>Example text</td>
      <td>Example text</td>
      <td>Example text</td>
    </tr>
  </tfoot>
</table>

<button class="tl"><b>Toggle table-layout: fixed</b></button>
</body>
<script type="text/javascript">
var tlBtn = document.querySelector('.tl'),
    tbl = document.getElementById('t');

tlBtn.addEventListener('click', function() {
  tbl.classList.toggle('fixed');
}, false);
</script>
</html>