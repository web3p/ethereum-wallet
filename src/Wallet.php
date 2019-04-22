<?php

/**
 * This file is part of ethereum-wallet package.
 * 
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3p\EthereumWallet;

use BitWasp\BitcoinLib\BIP39\BIP39;
use BitWasp\BitcoinLib\BIP39\BIP39WordList;
use Web3p\EthereumUtil\Util;

/**
 * TODO: export/import version 1, 2, 3 of wallet
 */
class Wallet
{
    /**
     * wordlist
     * 
     * @var \BitWasp\BitcoinLib\BIP39\BIP39WordList
     */
    protected $wordlist;

    /**
     * util
     * 
     * @var \Web3p\EthereumUtil\Util
     */
    protected $util;

    /**
     * privateKey
     * 
     * @var string
     */
    protected $privateKey;

    /**
     * mnemonic
     * 
     * @var string
     */
    protected $mnemonic;

    /**
     * publicKey
     * 
     * @var string
     */
    protected $publicKey;

    /**
     * address
     * 
     * @var string
     */
    protected $address;

    /**
     * allowedMnemonicLength
     * 
     * @var array
     */
    protected $allowedMnemonicLength = [
        12, 15, 18, 21, 24
    ];

    /**
     * masterSeed
     * 
     * @var string
     */
    protected $masterSeed = "Bitcoin seed";

    /**
     * chainCode
     * 
     * @var string
     */
    protected $chainCode;

    /**
     * construct
     * 
     * @param \BitWasp\BitcoinLib\BIP39\BIP39WordList $wordlist
     * @return void
     */
    public function __construct(BIP39WordList $wordlist)
    {
        $this->wordlist = $wordlist;
        $this->util = new Util;
    }

    /**
     * get
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }
        return false;
    }

    /**
     * set
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }
        return false;
    }

    /**
     * getMnemonic
     * 
     * @return string
     */
    public function getMnemonic()
    {
        return $this->mnemonic;
    }

    /**
     * getPrivateKey
     * 
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * getPublicKey
     * 
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * getAddress
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * setWordlist
     * 
     * @param BIP39WordList $wordlist
     * @return void
     */
    public function setWordlist(BIP39WordList $wordlist)
    {
        $this->wordlist = $wordlist;
    }

    /**
     * getChainCode
     * 
     * @return string
     */
    public function getChainCode()
    {
        return $this->chainCode;
    }

    /**
     * fromMnemonic
     * 
     * @param string $mnemonic
     * @return $this
     */
    public function fromMnemonic(string $mnemonic)
    {
        $entropy = BIP39::mnemonicToEntropy($mnemonic);
        $hash = hash_hmac("sha512", $entropy, $this->masterSeed);
        $privateKey = substr($hash, 0, 64);
        $chainCode = substr($hash, 64);
        $publicKey = $this->util->privateKeyToPublicKey($privateKey);
        $address = $this->util->publicKeyToAddress($publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mnemonic = $mnemonic;
        $this->address = $address;
        $this->chainCode = $chainCode;
        return $this;
    }

    /**
     * generate
     * 
     * @param int $mnemonicLength
     * @return $this
     */
    public function generate(int $mnemonicLength)
    {
        if (!in_array($mnemonicLength, $this->allowedMnemonicLength)) {
            throw new InvalidArgumentException("The mnemonic length wasn't allowed");
        }
        $entropyBitsLength = ($mnemonicLength * 11 * 32) / 33;
        $entropy = BIP39::generateEntropy($entropyBitsLength);
        $hash = hash_hmac("sha512", $entropy, $this->masterSeed);
        $privateKey = substr($hash, 0, 64);
        $chainCode = substr($hash, 64);
        $mnemonic = BIP39::entropyToMnemonic($entropy, $this->wordlist);
        $publicKey = $this->util->privateKeyToPublicKey($privateKey);
        $address = $this->util->publicKeyToAddress($publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mnemonic = $mnemonic;
        $this->address = $address;
        $this->chainCode = $chainCode;
        return $this;
    }
}