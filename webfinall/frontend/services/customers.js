var CustomersService = {
    populateService: function(){
        RestClient.get("customers",function(customers){
            let counter = 4;
            customers.forEach(customer => {
                $("#customers-list").append(`
                    <option value="${counter}">${customer.first_name} ${customer.last_name}</option>
                    `);
                    counter++;
            });
        }, function(xhr, status, error){
            console.error("Error fetching customers: " , error, status);
        });
    },
    addCustomer: function(){
        $("#add-customer-modal form").on("submit", function(e){
            e.preventDefault();
            let $data = {
                first_name: $("#first_name").val(),
                last_name: $("#last_name").val(),
                birth_date: $("#birth_date").val(),
            }
            RestClient.post("customers/add", $data, function(){
                alert("Customer added!");
                CustomersService.populateService();
                $("#add-customer-modal").modal("hide");
            }, function(xhr, status, error){
                console.error("Error adding a customer: " , error, status);
            });
        });
    },
    changeMeals: function(){
        $("#customers-list").on("change", function(){
            $("#customer-meals tbody").empty();
            const customerid = $(this).val();
            RestClient.get(`customer/meals/${customerid}`, function(meals){
                meals.forEach(meal =>{
                    $("#customer-meals tbody").append(`
                        <tr>
                        <td>${meal.name}</td>
                        <td>${meal.brand}</td>
                        <td>${meal.created_at}</td>
                        </tr>
                        `);
                });
            }, function(xhr, status, error){
                console.error("Error changing meals: " , error, status);
            });
        });
    }
    
}