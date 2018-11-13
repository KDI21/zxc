<style>
        .link_test
        {
          position:relative;
            }

        .title
        {
          display:none; height:100px; width:200px; background:#fffed2; border:1px solid #838231; padding:5px; position:absolute;
          margin-left:120px; top:5px; z-index:100;
            }
        .index
        {
          position:absolute; height:5px; width:10px; background:red; left:-5px;
            }
    </style>
    <a href="" title="#title_for_three_link" class="link_test">Наведите мышку</a>

    <div id="title_for_three_link" class="title">
        <div class="index"></div>
        <a href="google.com">google</a>
    </div>
    <script>
    $(document).ready(function(){

        // При наведении на ссылку
        $(".link_test").mouseover
        (function(){

            // Получаем ID блока, который нужно показать
            var title = $(this).attr("index");

            // Показываем блок
            $(this).append( $(title) );
            $(title).fadeIn();
        });

        // При уходе мышки со ссылки
        $(".link_test").mouseout
        (function(){

            // Получаем ID блока, который нужно показать
            var title = $(this).attr("title");

            // Скрываем блок
            $(title).fadeOut();

        });
    });
</script>
