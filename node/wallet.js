'use strict';

var bitcoin = require('bitcoinjs-lib')
  , bitcore = require('bitcore');


function Wallet()
{
    // null byte-o-cul
}

/**
 *
 * @param {String} seed    The passphrase
 * @param {String} network Could be 'livenet' or 'testnet' for example
 *
 * @returns {bitcore.HDPrivateKey}
 */
Wallet.prototype.create = function(seed, network)
{

    return bitcore.HDPrivateKey.fromSeed(bitcoin.crypto.sha256(seed), bitcore.Networks[network]);
};

//Wallet.prototype.getMultisigAddress = function(requiredSignatures, pubkeys)
//{
//    var redeemScript = bitcoin.scripts.multisigOutput(requiredSignatures, pubkeys);
//    var scriptPubKey = bitcoin.scripts.scriptHashOutput(redeemScript.getHash());
//
//    //return bitcoin.Address.fromOutputScript(scriptPubKey).toString();
//    return {
//        address: bitcoin.Address.fromOutputScript(scriptPubKey).toString(),
//        redeemScript: redeemScript,
//        scriptPubKey: scriptPubKey
//    };
//};

module.exports = Wallet;
