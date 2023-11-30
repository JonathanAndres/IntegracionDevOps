$(document).ready(function() {
    $(document).on("click", "#checkAll", function() {
        $(".itemRow").prop("checked", this.checked);
    });
    $(document).on("click", ".itemRow", function() {
        if ($(".itemRow:checked").length == $(".itemRow").length) {
            $("#checkAll").prop("checked", true);
        } else {
            $("#checkAll").prop("checked", false);
        }
    });
    var count = $(".itemRow").length;

    $(document).on("click", "#addRows", function() {
        var product = [];

        //Espacio para consumo Api
        obtenerDatosDeAPI()
            .then((datos) => {
                const inventario = datos;
                var product1;
                // Imprimir el objeto
                for (const key in inventario) {
                    product1 = inventario[key];
                    //console.log(`${product1.proname}`);
                    product[key] = {
                        id: product1.id,
                        name: product1.proname,
                        cantidad: product1.amount,
                    };
                }
            })
            .catch((error) => {
                console.error("Error general:", error);
            });
        //Espacio para consumo Api

        count++;
        var htmlRows = "";
        htmlRows += "<tr>";
        htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
        htmlRows +=
            '<td><input type="text" name="productCode[]" id="productCode_' +
            count +
            '" class="form-control" autocomplete="off" value="' +
            count +
            '" readonly></td>';
        //htmlRows += '<td><input type="text" name="productName[]" id="productName_' + count + '" class="form-control" autocomplete="off"></td>';
        htmlRows += `<td><select name="productName[]" id="productName_${count}" class="form-control">`;

        for (const key in product) {
            if (product.hasOwnProperty(key)) {
                const product1 = product[key];
                htmlRows += `<option value="${product1.id}">${product1.proname}</option>`;
                console.log(product1.proname);
            }
        }

        htmlRows += `</select></td>`;

        htmlRows +=
            '<td><input type="number" name="quantity[]" id="quantity_' +
            count +
            '" class="form-control quantity" autocomplete="off"></td>';
        htmlRows +=
            '<td><input type="number" name="price[]" id="price_' +
            count +
            '" class="form-control price" autocomplete="off"></td>';
        htmlRows +=
            '<td><input type="number" name="total[]" id="total_' +
            count +
            '" class="form-control total" autocomplete="off"></td>';
        htmlRows += "</tr>";
        $("#invoiceItem").append(htmlRows);
    });
    $(document).on("click", "#removeRows", function() {
        $(".itemRow:checked").each(function() {
            $(this).closest("tr").remove();
        });
        $("#checkAll").prop("checked", false);
        calculateTotal();
    });
    $(document).on("blur", "[id^=quantity_]", function() {
        calculateTotal();
    });
    $(document).on("blur", "[id^=price_]", function() {
        calculateTotal();
    });
    $(document).on("blur", "#taxRate", function() {
        calculateTotal();
    });
    $(document).on("blur", "#amountPaid", function() {
        var amountPaid = $(this).val();
        var totalAftertax = $("#totalAftertax").val();
        if (amountPaid && totalAftertax) {
            totalAftertax = totalAftertax - amountPaid;
            $("#amountDue").val(totalAftertax);
        } else {
            $("#amountDue").val(totalAftertax);
        }
    });
    $(document).on("click", ".deleteInvoice", function() {
        var id = $(this).attr("id");
        if (confirm("Are you sure you want to remove this?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                dataType: "json",
                data: { id: id, action: "delete_invoice" },
                success: function(response) {
                    if (response.status == 1) {
                        $("#" + id)
                            .closest("tr")
                            .remove();
                    }
                },
            });
        } else {
            return false;
        }
    });
});

function calculateTotal() {
    var totalAmount = 0;
    $("[id^='price_']").each(function() {
        var id = $(this).attr("id");
        id = id.replace("price_", "");
        var price = $("#price_" + id).val();
        var quantity = $("#quantity_" + id).val();
        if (!quantity) {
            quantity = 1;
        }
        var total = price * quantity;
        $("#total_" + id).val(parseFloat(total));
        totalAmount += total;
    });
    $("#subTotal").val(parseFloat(totalAmount));
    var taxRate = $("#taxRate").val();
    var subTotal = $("#subTotal").val();
    if (subTotal) {
        var taxAmount = (subTotal * taxRate) / 100;
        $("#taxAmount").val(taxAmount);
        subTotal = parseFloat(subTotal) + parseFloat(taxAmount);
        $("#totalAftertax").val(subTotal);
        var amountPaid = $("#amountPaid").val();
        var totalAftertax = $("#totalAftertax").val();
        if (amountPaid && totalAftertax) {
            totalAftertax = totalAftertax - amountPaid;
            $("#amountDue").val(totalAftertax);
        } else {
            $("#amountDue").val(subTotal);
        }
    }
}

//Consumo Api
async function obtenerDatosDeAPI() {
    try {
        const respuesta = await fetch(
            "http://localhost:8080/ApisDevOps/Inventario/inventario/read"
        );

        if (!respuesta.ok) {
            throw new Error(
                `Error al obtener los datos. Código de estado: ${respuesta.status}`
            );
        }

        const datosJson = await respuesta.json();
        return datosJson.Product;
    } catch (error) {
        console.error("Error en la solicitud:", error.message);
        throw error;
    }
}