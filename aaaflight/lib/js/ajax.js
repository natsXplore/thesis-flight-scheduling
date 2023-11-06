function showAjaxpo(destination, var_name, var_data, results) {
    if (var_data == "") {
        document.getElementById(results).innerHTML = "";
        document.getElementById("savenewstock").style.visibility = "hidden";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("account_no").value =  xmlhttp.responseText;
                document.getElementById(results).innerHTML = xmlhttp.responseText;
                document.getElementById(results + "1").value = xmlhttp.responseText;
                if (xmlhttp.responseText == "Duplicate Record Found!") {
                    document.getElementById("savenewstock").style.visibility = "visible";

                }
            }
        }
        xmlhttp.open("GET", destination + "?" + var_name + "=" + var_data, true);
        xmlhttp.send();
    }
}

function showAjax(destination, var_name, var_data, results) {
    if (var_data == "") {
        document.getElementById(results).innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("account_no").value =  xmlhttp.responseText;
                document.getElementById(results).innerHTML = xmlhttp.responseText;
                document.getElementById(results + "1").value = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", destination + "?" + var_name + "=" + var_data, true);
        xmlhttp.send();
    }
}




function showAjax2(destination, var_name, var_data) {
    if (var_data == "") {
        document.getElementById(results).innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var str = xmlhttp.responseText;
                var res = str.split(" = ");
                for (x = 0; x <= res.length - 1; x = x + 2) {
                    document.getElementById(res[x]).value = res[x + 1];
                }
            }
        }
        xmlhttp.open("GET", destination + "?" + var_name + "=" + var_data, true);
        xmlhttp.send();
    }
}

function showAjax3(destination, var_name, var_data, results) {
    if (var_data == "") {
        document.getElementById(results).innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("account_no").value =  xmlhttp.responseText;
                document.getElementById(results).innerHTML = xmlhttp.responseText;

            }
        }
        xmlhttp.open("GET", destination + "?" + var_name + "=" + var_data, true);
        xmlhttp.send();
    }
}

function sendID(destination, var_name, var_data) {

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET", destination + "?" + var_name + "=" + var_data, true);
    xmlhttp.send();
}

function addRowHandlers(tableid, startrow, col, filelink) {
    var table = document.getElementById(tableid);
    var rows = table.getElementsByTagName("tr");

    for (i = startrow; i < rows.length; i++) {
        var currentRow = table.rows[i];
        var createClickHandler =
            function(row) {
                return function() {
                    var cell = row.getElementsByTagName("td")[col];
                    var id = cell.innerHTML;
                    window.location = filelink + '?id=' + id;
                };
            };

        currentRow.onclick = createClickHandler(currentRow);
    }
}

//usage class="hidelink"
$(function() {
    $("a.hidelink").each(function(index, element) {
        var href = $(this).attr("href");
        $(this).attr("hiddenhref", href);
        $(this).removeAttr("href");
    });
    $("a.hidelink").click(function() {
        url = $(this).attr("hiddenhref");
        window.open(url, '_self');
    })
});

$.fn.extend({
    treed: function(o) {

        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';

        if (typeof o != 'undefined') {
            if (typeof o.openedClass != 'undefined') {
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined') {
                closedClass = o.closedClass;
            }
        };

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function() {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function(e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function() {
            $(this).on('click', function() {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function() {
            $(this).on('click', function(e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function() {
            $(this).on('click', function(e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

(function() {

    /**
     * Formats container element - can be overwritten in options
     * @param: container element (default: div)
     * @return: formatted container element 
     */
    function formatContainer(container) {
        container.className = 'container';
        return container;
    }
    /**
     * Formats UL element - can be overwritten in options
     * @param: UL element
     * @return: formatted LI element 
     */
    function formatUl(ul) {
        ul.className = 'ul-item';
        return ul;
    }

    /**
     * Formats LI element - can be overwritten in options 
     * @param: LI element
     * @return: formatted LI element 
     */
    function formatLi(li) {
        li.className = 'li-item';
        return li;
    }

    /**
     * Formats object property text - can be overwritten in options 
     * @param: text node object property
     * @return: strong element with property name inside 
     */
    function formatProperty(prop) {
        var strong = document.createElement('strong');
        strong.appendChild(prop);
        return strong;
    }

    /**
     * Formats object/array value text - can be overwritten in options 
     * @param: text node value 
     * @return: span element with value text inside 
     */
    function formatValue(val, prop) {
        var span = document.createElement('span');
        span.appendChild(val);
        return span;
    }

    /**
     * Options object
     */
    var _options = {
        container: 'div',
        formatContainer: formatContainer,
        formatUl: formatUl,
        formatLi: formatLi,
        formatProperty: formatProperty,
        formatValue: formatValue
    };

    function JSON2HTMLList(json, options) {

        for (var opt in options) {
            if (options.hasOwnProperty(opt)) {
                _options[opt] = options[opt];
            }
        }

        var container = document.createElement(_options.container);
        container = _options.formatContainer(container);

        function walk(obj, parentElm) {
            if (typeof(obj) === 'object' && obj !== null && obj.constructor === Object) {
                var ul = document.createElement('ul');
                ul = _options.formatUl(ul);
                var hasCount = 0;
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        var li = document.createElement('li');
                        li = _options.formatLi(li);
                        ul.appendChild(li);

                        if (typeof(obj[prop]) !== 'object' || obj[prop] === null) {

                            var propText = document.createTextNode(prop);
                            propText = _options.formatProperty(propText);

                            li.appendChild(propText);

                            var valueText = document.createTextNode(obj[prop]);
                            valueText = _options.formatValue(valueText, prop);

                            li.appendChild(valueText);

                            hasCount++;
                        } else {
                            var propText = document.createTextNode(prop);
                            propText = _options.formatProperty(propText);

                            li.appendChild(propText);

                            walk(obj[prop], li);
                        }
                    }
                }
                parentElm.appendChild(ul);

            } else if (typeof(obj) === 'object' && obj !== null && obj.constructor === Array) {
                var ul = document.createElement('ul');
                ul = _options.formatUl(ul);

                var hasCount = 0;
                for (var i = 0; i < obj.length; i++) {

                    if (typeof(obj[i]) !== 'object' || obj[i] === null) {
                        var li = document.createElement('li');
                        li = _options.formatLi(li);

                        ul.appendChild(li);

                        var valueText = document.createTextNode(obj[i]);
                        valueText = _options.formatValue(valueText, i);

                        li.appendChild(valueText);

                        hasCount++;
                    } else {
                        walk(obj[i], parentElm);
                    }
                }
                parentElm.appendChild(ul);
            }
        }


        walk(json, container);

        return container;

    }

    if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
        module.exports = JSON2HTMLList;
    } else {
        if (!('JSON2HTMLList' in window)) {
            window.JSON2HTMLList = JSON2HTMLList;
        }
    }


})();