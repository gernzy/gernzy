const errorTemplate = message => `
    <form id="payment-form">
    <div id="card-element">
        <!-- Elements will create input elements here -->
    </div>

    <!-- We'll put the error messages in this element -->
    <div id="card-errors" role="alert"></div>

    <button id="submit">Pay</button>
    </form>
`;

export default errorTemplate;
