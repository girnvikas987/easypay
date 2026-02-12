function handleForm(event) { event.preventDefault(); } 
// form.addEventListener('submit', handleForm);

const Web3Modal = window.Web3Modal.default;
const WalletConnectProvider = window.WalletConnectProvider.default;
const EvmChains = window.evmChains;
const Fortmatic = window.Fortmatic;
const ethers = window.ethers

// Web3modal instance
let web3Modal

// Chosen wallet provider given by the dialog window
let provider;

let chainId;

let calculateAll

// Address of the selected account
let selectedAccount;

let userAddress;

let userBalance;
/**
 * Setup the orchestra
 */
function init() {

  console.log("Initializing example");
  console.log("WalletConnectProvider is", WalletConnectProvider);
  console.log("Fortmatic is", Fortmatic);

  // Tell Web3modal what providers we have available.
  // Built-in web browser provider (only one can exist as a time)
  // like MetaMask, Brave or Opera is added automatically by Web3modal
  const providerOptions = {
    walletconnect: {
      package: WalletConnectProvider,
      display: {
        name: "Wallet Connect"
      },
      options: {
        // Mikko's test key - don't copy as your mileage may vary
        infuraId: "5f40cd78a0004e3dbe19bd078e6d520a",
      }
    },

    fortmatic: {
      package: Fortmatic,
      options: {
        // Mikko's TESTNET api key
        key: "pk_test_391E26A3B43A3350"
      }
    }
  };

  web3Modal = new Web3Modal({
    cacheProvider: false, // optional
    providerOptions, // required
  });

}


/**
 * Kick in the UI action after Web3modal dialog has chosen a provider
 */
async function fetchAccountData() {

  // Get a Web3 instance for the wallet
  const web3 = new Web3(provider);

  console.log("provider: ",provider);
  window.localStorage.provider = new ethers.providers.Web3Provider(provider);
  console.log("Web3 instance is", web3);
  // Get connected chain id from Ethereum node
  chainId = await web3.eth.getChainId();
  console.log(chainId);
	 
  // Load chain information over an HTTP API
  const chainData = await EvmChains.getChain(chainId);
  // document.querySelector("#network-name").textContent = chainData.name;

  // Get list of accounts of the connected wallet
  const accounts = await web3.eth.getAccounts();

  // MetaMask does not give you all accounts, only the selected account
  console.log("Got accounts", accounts);
  selectedAccount = accounts[0];

  // document.querySelector("#selected-account").textContent = selectedAccount;

  // Get a handl
  // const template = document.querySelector("#template-balance");
  // const accountContainer = document.querySelector("#accounts");

  // Purge UI elements any previously loaded accounts
  // accountContainer.innerHTML = '';

  // Go through all accounts and get their ETH balance
  const rowResolvers = accounts.map(async (address) => {
    userAddress = address;
    const balance = await web3.eth.getBalance(address);
    // ethBalance is a BigNumber instance
    // https://github.com/indutny/bn.js/
    const ethBalance = web3.utils.fromWei(balance, "ether");
    const humanFriendlyBalance = parseFloat(ethBalance).toFixed(4);
    userBalance = humanFriendlyBalance;

    // Fill in the templated row and put in the document
    // const clone = template.content.cloneNode(true);
    // clone.querySelector(".address").textContent = address;
    // clone.querySelector(".balance").textContent = humanFriendlyBalance;
    // accountContainer.appendChild(clone);
  });
  calculateAll = setInterval(calculateToken, 1000);
  calculateAll;

  // Because rendering account does its own RPC commucation
  // with Ethereum node, we do not want to display any results
  // until data for all accounts is loaded
  await Promise.all(rowResolvers);

  // Display fully loaded UI for wallet data
  document.querySelector("#prepare").style.display = "none";
  document.querySelector("#connected").style.display = "block";
}



/**
 * Fetch account data for UI when
 * - User switches accounts in wallet
 * - User switches networks in wallet
 * - User connects wallet initially
 */
async function refreshAccountData() {

  // If any current data is displayed when
  // the user is switching acounts in the wallet
  // immediate hide this data
  document.querySelector("#connected").style.display = "none";
  document.querySelector("#prepare").style.display = "block";

  // Disable button while UI is loading.
  // fetchAccountData() will take a while as it communicates
  // with Ethereum node via JSON-RPC and loads chain data
  // over an API call.
  document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
  await fetchAccountData(provider);
  document.querySelector("#btn-connect").removeAttribute("disabled")
}


/**
 * Connect wallet button pressed.
 */
async function onConnect() {

  console.log("Opening a dialog", web3Modal);
  try {
    provider = await web3Modal.connect();
  } catch(e) {
    console.log("Could not get a wallet connection", e);
    return;
  }

  // Subscribe to accounts change
  provider.on("accountsChanged", (accounts) => {
    fetchAccountData();
  });

  // Subscribe to chainId change
  provider.on("chainChanged", (chainId) => {
    fetchAccountData();
  });

  // Subscribe to networkId change
  provider.on("networkChanged", (networkId) => {
    fetchAccountData();
  });

  await refreshAccountData();
}

/**
 * Disconnect wallet button pressed.
 */
async function onDisconnect() {

  console.log("Killing the wallet connection", provider);

  // TODO: Which providers have close method?
  if(provider.close) {
    await provider.close();

    // If the cached provider is not cleared,
    // WalletConnect will default to the existing session
    // and does not allow to re-scan the QR code with a new wallet.
    // Depending on your use case you may want or want not his behavir.
    await web3Modal.clearCachedProvider();
    provider = null;
  }
  clearInterval(calculateAll);
  selectedAccount = null;

  // Set the UI back to the initial state
  document.querySelector("#prepare").style.display = "block";
  document.querySelector("#connected").style.display = "none";
}


/**
 * Main entry point.
 */
window.addEventListener('load', async () => {
  init();
  document.querySelector("#btn-connect").addEventListener("click", onConnect);
  document.querySelector("#btn-disconnect").addEventListener("click", onDisconnect);
});

// window.onload = function() {            
//     setInterval(calculateToken, 1000);
// }

console.log("ethers instance: ", ethers);

 
function truncate(input) {
    if (input.length > 5) {
       return input.substring(0, 6) + '.......' + input.substring(input.length - 5);
    }
    return input;
 };


 function toFixed(x) {
  if (Math.abs(x) < 1.0) {
    var e = parseInt(x.toString().split('e-')[1]);
    if (e) {
        x *= Math.pow(10,e-1);
        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
    }
  } else {
    var e = parseInt(x.toString().split('+')[1]);
    if (e > 20) {
        e -= 20;
        x /= Math.pow(10,e);
        x += (new Array(e+1)).join('0');
    }
  }
  return x;
}

async function calculateToken() {
	//alert();
  const provider1 = new ethers.providers.Web3Provider(provider);
  var requestOptions = {
    method: 'GET',
    redirect: 'follow'
  };

  let price = [];
  fetch("https://api.binance.com/api/v3/ticker/price?symbol=BNBUSDT", requestOptions)
    .then(response => response.text())
    .then(result => {price.push(JSON.parse(result).price); })
    .catch(error => console.log('error', error));

  // console.log(contractInstance);
  (async () => {
      // Modern dapp browsers...
      if (provider) {
          try {
            var address = userAddress;
            // console.log(price[0]);
            document.getElementById("ref").innerHTML=("register?ref="+userAddress);
            var url_string = window.location.href;
            var url = new URL(url_string);
            var c = url.searchParams.get("ref");
            
            if(c==null){
              document.getElementById("ref1").innerHTML="No Valid Joining ID Link - Default 0xE8557fe9F04bC76D58189af53bA4768063c62633 address will be used for joining";
            }else{
              document.getElementById("ref1").innerHTML=c;
            }

       const bnbbalhex = await provider1.getBalance(userAddress);      
			  
          } catch (error) {
              console.log(error);
          }

      }

      // Non-dapp browsers...
      else {
         // document.getElementById("priceToken").value = "Connect to Wallet";
      }
  })();
}

async function register22(){
  console.log(2342346);
}

async function register()
{

  //alert();
  const provider1 = new ethers.providers.Web3Provider(provider);
  const bnbbalhex = await provider1.getBalance(userAddress);
  
  const bnbBal = (parseInt(bnbbalhex._hex,16));
  const address = userAddress;

  var url_string = window.location.href;
  var url = new URL(url_string);
  var c = url.searchParams.get("ref");

  if(c==null){
    c="0x06f4b96Ac79f9904a3E047b702c27e04ed0D7558"
  }

  if( chainId == 137 || chainId == 137){
    if(provider
    ){
        
    }else{
        alert("Please connect to Metamask/Trustwallet and switch to Binance Network");
        return false;
    }
  }else{
      alert('Connect To Binance Chain Mainnet  Mainnet');
      return false;
  }

  var bnbValue = 1;//toFixed(document.getElementById("bnb").value * 1e18);
  var pkg = document.getElementById("pkg").value;

  

  if( chainId == 137 || chainId == 137){
      if( provider
      ){
          const hash = await provider1.transfer(String(c),{value:String(bnbValue)});
          await provider1.waitForTransaction(hash.hash,1);
          alert("Token Purchased Sucessfully !");
      }else{
          alert("Please connect to Metamask/Trustwallet and switch to Binance Network");
          return false;
      }
  }else{
      alert('Connect To Binance Chain Mainnet');
      return false;
  }
}


   

async function updateTimer() {

  const provider1 = new ethers.providers.Web3Provider(provider);
  
  
  future = 1635004200000;

now = Date.now();
diff = future - now;

days = Math.floor(diff / (1000 * 60 * 60 * 24));
hours = Math.floor(diff / (1000 * 60 * 60));
mins = Math.floor(diff / (1000 * 60));
secs = Math.floor(diff / 1000);

d = days;
h = hours - days * 24;
m = mins - hours * 60;
s = secs - mins * 60;

if(now<future){
	document.getElementById("timer").innerHTML =
	' ' + d + ' Days' +
	' ' + h + ' Hours' +
	' ' + m + ' Minutes' +
	' ' + s + ' Seconds';
  
  }else{
  
  }
}
 