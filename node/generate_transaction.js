#!/usr/bin/env node

'use strict';

var bitcore = require('bitcore');

var params   = JSON.parse(process.argv[2]);
//console.error(params.changeAddress);
var transaction = new bitcore.Transaction();

// Add all inputs
params.inputs.forEach(function(input) {
    // Recover the address object
    var address = new bitcore.Address(input.address.pub_keys, input.address.application.keychain.sign_required);

    transaction.from({
            address: address.toString(),
            txid:    input.txid,
            outputIndex: input.index,
            script: new bitcore.Script(address).toHex(),
            amount: input.amount
        },
        input.address.pub_keys,
        input.address.application.keychain.sign_required
    );
});

// Add all outputs
params.outputs.forEach(function(output) {
    transaction.to(output.to_address, parseFloat(output.amount).toFixed(8) * 100000000); // Convert amount to satoshi
});

// Add change address if needed
if (params.changeAddress) {
    transaction.change(params.changeAddress.value);
    //transaction.to('3Cex1PTvqPzwm989zq8Q3xuqS2rTCnHFBC', 407000);
}

console.error(transaction);

        //.fee(10000)
        //.to('1CDFGQxhWFzghkXzv4uf9yCWxDPSvWudiH', 10000)
        //.to('3Cex1PTvqPzwm989zq8Q3xuqS2rTCnHFBC', 407000)
        //.sign('L43XZrsKz8h2zyEWJxyx27VZ9Zje7CijgZ8ZjUe6xn7gny86HfE3')
        //.sign('L2wxUn3Y9CBSzmcD9KHUkVZ8zPs84HtnRCRJtZiyjKzHxxbPB5Nk')