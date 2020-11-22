//color of charts
let colors = [ 
    '#4661EE',
    '#EC5657',
    '#1BCDD1',
    '#8FAABB',
    '#B08BEB',
    '#3EA0DD',
    '#F5A52A',
    '#23BFAA',
    '#FAA586',
    '#EB8CC6',
    "#2F4F4F",
   "#008080",
   "#2E8B57",
   "#3CB371",
   "#90EE90"
];

/* sidebar */
let widthSidebar = 250;
$(document).ready(function(){
    widthSidebar = $('aside').width();
})

/* print */
$(document).on('click', '.fa-print', function(){
    window.print();
})

$(document).on('click', '#btn-shrink-sidebar', function(){
    $('aside').css('flex-basis','50px');
    $(this).addClass('hide');
    $('.sidebar-link-description').hide();
    $('#btn-expand-sidebar').removeClass('hide');
    $('.page-content').css("margin-left", "50px");
})

$(document).on('click', '#btn-expand-sidebar', function(){
    $('aside').css('flex-basis', widthSidebar+'px');
    $(this).addClass('hide');
    $('.sidebar-link-description').show();
    $('#btn-shrink-sidebar').removeClass('hide');
    $('.page-content').css("margin-left", "250px");
})

/* dropdown */
$(document).on('click', function(evt){
    evt.stopPropagation();
    let dropdownContainer = $('.dropdown');
    if(dropdownContainer.has(evt.target).length == 0){
        $('.dropdown-content').css("display", "none");
    }
})

$(document).on('keyup', '.dropdown-input', function(evt){
    let value = evt.target.value;
    if(!value){
        $(this).siblings('.dropdown-content').css("display", "none");
    }else{
        $(this).siblings('.dropdown-content').css("display", "block");
    }
})


/* alert */
$(document).on("click", ".closebtn", function(){
    $(this).parent().css("display","none");
})

$(document).on('click.global-dropdown-content-item', '.dropdown-content-item', function(evt){
    let value = $(this).text();
    let dropdownInput = $(this).parent().siblings('.dropdown-input');
    dropdownInput.attr("old-value",  dropdownInput.val());
    dropdownInput.val(value);
    $(this).parent().css("display", "none");
})

/* Modal */
$(document).on('click', '.btn-toggle-modal', function(evt){
    let modalTarget = $(this).attr("modal-target");
    $('#'+modalTarget).css("display", "block");
})

$(document).on('click', '.close-modal', function(){
    $(this).parent().parent().parent().css("display","none");
    $(this).parent().parent().parent().trigger("hide");
})

$(document).on('click', '.modal', function(evt){
    let modalContentClass = $(this).children(":first").attr("class");
     let modalContentContainer = $('.'+modalContentClass);
    if(modalContentContainer.has(evt.target).length == 0){
        $(this).css("display", "none");
    }
})

/* accordion */

$(document).on('click', '.accordion', function(){
    $(this).toggleClass('accordion-active');
    if($(this).next('.panel').css("maxHeight") == '0px'){
        $(this).next('.panel').css("maxHeight",$(this).next('.panel')[0].scrollHeight+'px');
    }else{
        $(this).next('.panel').css("maxHeight", "0px");
    }
})

/* table pagination and filter */
/* function is invoked by pageName.js */
function tablePagination(idTable){
    let currentPage = 1;
    filterHideShowRow();
    
    $(document).on('click', '#numberRow', function(evt){
        currentPage = 1;
        filterHideShowRow();
    });

    $(document).on('keyup','#tableSearch',function(evt){
        console.log("trigger");
        currentPage = 1;
        filterHideShowRow()
    })

    $(document).on('click','.pagination li', function(evt){
        evt.stopImmediatePropagation();
        evt.preventDefault();
        let pageNum = $(this).attr('data-page');

        if(pageNum == "prev"){
            if(currentPage == 1){
                return;
            }else{
                currentPage--;
            }
        }

        else if(pageNum == "next"){
            if(currentPage >= $('.pagination li').length -2){
                return;
            }else{
                currentPage++;
            }
        }else{
            currentPage = pageNum;
        }

        filterHideShowRow();
    })

    function filterHideShowRow(){
        let trNums = 0;
        let maxRowToShowPerPage = $('#numberRow').val();
        let keywordSearch = $('#tableSearch').val();
        let startIndexToShow = (currentPage-1) * maxRowToShowPerPage;
        let endIndexToShow = currentPage * maxRowToShowPerPage;
        let numberOfDataFound = 0;
        let jum = 0;
        $(idTable+' tbody tr').each(function(){
            let showTr = false;
            $.each(this.cells, function(index){
                if(String($(this).text()).toLowerCase().includes(keywordSearch.toLowerCase())){
                    numberOfDataFound++;
                    showTr = true;
                    $(this).parent().show();
                    return false;
                }
                //untuk cell yang memiliki row berupa input text
                if($(this).find('input[type="text"]').length !== 0){
                    if($(this).find('input[type="text"]').val().toLowerCase().includes(keywordSearch.toLowerCase())){
                        numberOfDataFound++;
                        showTr = true;
                        $(this).parent().parent().show();
                        return false;
                    }
                }

                //untuk cell yang memiliki row berupa button input submit 
                if($(this).find('input[type="submit"]').length !== 0){
                    if($(this).find('input[type="submit"]').val().toLowerCase().includes(keywordSearch.toLowerCase())){
                        numberOfDataFound++;
                        showTr = true;
                        $(this).closest("tr").show();
                        return false;
                    }
                }

            })
           if(!showTr){
                $(this).hide();
           }else{
               $(this).show();
           }
           
        });
        
        $(idTable+ ' tbody tr').not(":hidden").each(function(){
            $(this).children(":first").text(trNums+1);
            if(trNums >= startIndexToShow && trNums < endIndexToShow){
                $(this).show();
            }else{
                $(this).hide();
            }
            trNums++;
        });

        $('#numberOfDataFound').text(numberOfDataFound);
        if( numberOfDataFound > maxRowToShowPerPage){
            //remove all li except first li ( < prev button) and last li (> next button)
            $('.pagination').find("li").slice(1, -1).remove();
            let pageNum = Math.ceil(numberOfDataFound/maxRowToShowPerPage);
            for(let i=1; i <= pageNum;i++){
                $('.pagination #next').before(`<li class='pagination-item' data-page='${i}'>${i}</li>`).show();
            }
            $('.pagination').show();
        }else{
            $('.pagination').hide();
        }

        $(`.pagination-item[data-page='${currentPage}']`).addClass('pagination-item-active');
    }

}


/*filter input */
function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
        [].forEach.call(textbox, tx => {
            tx.addEventListener(event, function() {
                if (inputFilter(this.value)) {
                  this.oldValue = this.value;
                  this.oldSelectionStart = this.selectionStart;
                  this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                  this.value = this.oldValue;
                  this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                  this.value = "";
                }
              }); 
        });
    });
}