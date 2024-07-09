<style>
    .dibsy-component {
     background: #EEF1F6;
  box-shadow: 0px 1px 0px rgb(0 0 0 / 10%), 0px 2px 4px rgb(0 0 0 / 10%),
    0px 4px 8px rgb(0 0 0 / 5%);
  border-radius: 4px;
  padding: 13px;
  border: 1px solid #ccc; /* Add border style here */
  transition: 0.15s border-color cubic-bezier(0.4, 0, 0.2, 1);
  font-weight: 500;
    }
    /* Adds styles when the field receives focus for the first time. */

        .dibsy-component.is-touched {
          border-color: #0077ff;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the fields receive focus and removed when the fields have lost focus */
        
        .dibsy-component.has-focus {
          border-color: #0077ff;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the input values in the field are legal  */
        
        .dibsy-component.is-invalid {
          border-color: #ff1717;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the input values in the field are illegal */
        
        .dibsy-component.is-valid {
          border-color: green;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
</style>

<!--Your Checkout Form-->

<div class="row">
    <div class="card">
         <div class="card-body">
             <form>
                 <div class="row">
                    <div class="col-md-12">
                         <div class="form-group">
                              <label>Name</label>
                              <div id="card-holder"></div>
                              <div id="card-holder-error"></div>
                         </div>
                    </div>
                    <div class="col-md-12">
                         <div class="form-group">
                              <label>Card No.</label>
                              <div id="card-number"></div>
                              <div id="card-number-error"></div>  
                         </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                              <label>Expiry Date.</label>
                              <div id="expiry-date"></div>
                              <div id="expiry-date-error"></div> 
                         </div>
                    </div>
                    <div class="col-md-6">     
                         <div class="form-group">
                              <label>CVC.</label>
                              <div id="verification-code"></div>
                              <div id="verification-code-error"></div> 
                         </div>
                    </div>     
                </div>
                <button id="payment-button" class="btn" type="submit">Pay</button>
            </form>
        </div>
    </div>
</div>
     

   

   
  
    
  
    
  
 

<script src="https://cdn.dibsy.one/js/dibsy-2.0.0.js"></script>
<script>

    (async () => {
        const dibsy = await Dibsy("pk_test_765f54ec5f7a51f493ca182b4ee3b0b2a9d4", { locale: "en_US" });
        
         const options = {
                styles: {
                    base: {
                        color: '#333', // Text color
                        fontFamily: 'Arial, sans-serif', // Font family
                        fontSize: '16px', // Font size
                        '::placeholder': {
                            color: '#ccc', // Placeholder color
                        },
                    },
                    invalid: {
                        color: '#ff0000', // Color for invalid input
                    },
                },
                // Other configuration options can go here
            };
        
            const cardNumber = dibsy.createComponent("cardNumber", options);
            cardNumber.mount("#card-number");
        
            const cardHolder = dibsy.createComponent("cardHolder", options);
            cardHolder.mount("#card-holder");
        
            const expiryDate = dibsy.createComponent("expiryDate", options);
            expiryDate.mount("#expiry-date");
        
            const verificationCode = dibsy.createComponent("verificationCode", options);
            verificationCode.mount("#verification-code");
        
          // Error Handling
          var cardNumberError = document.getElementById("card-number-error");
          cardNumber.addEventListener("change", function (event) {
            if (event.error && event.touched) {
              cardNumberError.textContent = event.error;
            } else {
              cardNumberError.textContent = "";
            }
          });
          
          
          // Submit Form
          const form = document.querySelector('form');

            form.addEventListener('submit', async e => {
                e.preventDefault();
            
                const { token, error } = await dibsy.cardToken();
                alert(token)
                if (error) {
                    // Handle error here (e.g., display error message to the user)
                    console.error('Error:', error);
                } else {
                    // Tokenization successful, you can send the token to your server for further processing
                    console.log('Token:', token);
            
                    // Append the token to a hidden input field in the form and then submit the form to your server
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'token';
                    tokenInput.value = token;
                    form.appendChild(tokenInput);
            
                    // Submit form to the server
                    
                }
            });
          
           
              
          
          
            
    })();
    //const dibsy = await Dibsy("pk_test_765f54ec5f7a51f493ca182b4ee3b0b2a9d4", { locale: "en_US" });
    
    
    
      
      
      
</script>