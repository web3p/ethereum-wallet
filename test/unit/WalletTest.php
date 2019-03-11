<?php

namespace Test\Unit;

use Test\TestCase;
use BitWasp\BitcoinLib\BIP39\BIP39EnglishWordList;
use Web3p\EthereumWallet\Wallet;

class WalletTest extends TestCase
{
    /**
     * testGenerate
     * 
     * @return void
     */
    public function testGenerate()
    {
        $wordlist = new BIP39EnglishWordList;
        $wallet = new Wallet($wordlist);
        $wallet = $wallet->generate();
        $this->assertEquals(64, strlen($wallet->privateKey));
        $this->assertEquals(42, strlen($wallet->address));
        $mnemonic = preg_split("/\s/", $wallet->mnemonic);
        $this->assertEquals(24, count($mnemonic));
    }
}
