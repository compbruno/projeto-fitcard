function inputHandler(masks, max, event) {
    var c = event.target
    var v = c.value.replace(/\D/g, '')
    var m = c.value.length > max ? 1 : 0
    VMasker(c).unMask()
    VMasker(c).maskPattern(masks[m])
    c.value = VMasker.toPattern(v, masks[m])
}

if (document.getElementById("ddd")) {
    var dddMask = ['(99)', '(99)']
    var ddd = document.querySelector('#ddd')
    VMasker(ddd).maskPattern(dddMask[0])
    ddd.addEventListener('input', inputHandler.bind(undefined, dddMask, 4), false)

    var telMask = ['9999-99999', '99999-9999']
    var tel = document.querySelector('#numTelefone')
    VMasker(tel).maskPattern(telMask[0])
    tel.addEventListener('input', inputHandler.bind(undefined, telMask, 10), false)

    var cnpjMask = ['99.999.999/9999-99', '99.999.999/9999-99']
    var cnpj = document.querySelector('#cnpj')
    VMasker(cnpj).maskPattern(cnpjMask[0])
    cnpj.addEventListener('input', inputHandler.bind(undefined, cnpjMask, 10), false)

    var contaMask = ['99.999-9', '99.999-9']
    var conta = document.querySelector('#conta')
    VMasker(conta).maskPattern(contaMask[0])
    conta.addEventListener('input', inputHandler.bind(undefined, contaMask, 10), false)

    var agenciaMask = ['999-9', '999-9']
    var agencia = document.querySelector('#agencia')
    VMasker(agencia).maskPattern(agenciaMask[0])
    agencia.addEventListener('input', inputHandler.bind(undefined, agenciaMask, 10), false)
}

function validarEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    if (re.test(String(email).toLowerCase())) {
        document.getElementById("email").style.borderColor = "#0f0"
    } else {
        showModal("Email inválido", "Por favor, verifique os dados inseridos.")
        document.getElementById("email").style.borderColor = "#f00"
    }
}

function validarForm() {
    let categoria           = document.forms["form_estabelecimento"]["categoria"].value
    let razaoSocial         = document.forms["form_estabelecimento"]["razao_social"].value
    let cnpjEstabelecimento = document.forms["form_estabelecimento"]["cnpj"].value
    let numTelefone         = document.forms["form_estabelecimento"]["numTelefone"].value
    
    if(categoria === "S") {
        if ((razaoSocial == "") || (cnpjEstabelecimento == "")) {
            showModal("Os campos Razão Social e CNPJ são obrigatórios")
            return false
        }
    } else {
        if ((razaoSocial == "") || (cnpjEstabelecimento == "" || (numTelefone = ""))) {
            showModal("Os campos Razão Social, CNPJ e telefone são obrigatórios")
            return false
        }
    }
    
}

function verificaCategoria(categoria) {
    if (categoria === "S") {
        document.getElementById("ddd").required = true
        document.getElementById("numTelefone").required = true
    } else {
        document.getElementById("ddd").required = false
        document.getElementById("numTelefone").required = false
    }
}

function showModal(titulo, corpo = "Por favor, verifique os dados inseridos.") {
    document.getElementById("tituloModal").innerHTML = titulo
    document.getElementById("txtModal").innerHTML = corpo
    $("#modalValidacao").modal("show")
}

function dismissModal() {
    $("#modalValidacao").modal("hide")
}

function validarCNPJ(cnpj) {
    document.getElementById("cnpj").style.borderColor = "#f00"
    cnpj = cnpj.replace(/[^\d]+/g,'')
    if (cnpj == '') {
        showModal("CNPJ inválido", "Por favor, verifique os dados inseridos.")
        return false
    }
    if (cnpj.length != 14) {
        showModal("CNPJ inválido", "Por favor, verifique os dados inseridos.")
        return false
    }
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || cnpj == "11111111111111" || cnpj == "22222222222222" || cnpj == "33333333333333" || cnpj == "44444444444444" || cnpj == "55555555555555" || cnpj == "66666666666666" || cnpj == "77777777777777" || cnpj == "88888888888888" || cnpj == "99999999999999") {
        showModal("CNPJ inválido", "Por favor, verifique os dados inseridos.")
        return false
    }
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--;
    if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) {
        showModal("CNPJ inválido", "Por favor, verifique os dados inseridos.")
        return false
    }
    tamanho = tamanho + 1
    numeros = cnpj.substring(0,tamanho)
    soma = 0
    pos = tamanho - 7
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) {
            pos = 9
        }
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) {
        showModal("CNPJ inválido", "Por favor, verifique os dados inseridos.")
        return false
    }
    document.getElementById("cnpj").style.borderColor = "#0f0"
    return true
}