$(document).ready((evt) => {
  var id_user = $("#id_user").val()
  var tp_user = $("#tp_user").val()
  var id_muni = $("#id_muni").val()
  var dt_compet = mudarFormato($("#info-dtcompet").val()).trim()
  const div_modal = $(".generic-recolhimento")
  const waiting_recolhimento = $(".waiting-recolhimento")
  var npage = $("#sel-page").val() || 1

  const total_records = $("input#info-total")

  var table = $("table#tabela-agencias")

  var tfoot = table.find("tfoot")
  var nrows = tfoot.children().find("#sel-linhas > option:selected").val()

  // Inicialização da interface
  initializeUI()

  // Função para inicializar a interface com melhorias visuais
  function initializeUI() {
    // Adiciona classes para melhorar a aparência
    $("table#tabela-agencias").addClass("table-hover")

    // Melhora a aparência dos botões de paginação
    $("#first, #prev, #next, #last").addClass("pagination-button")

    // Adiciona tooltips para melhor usabilidade
    $("#first").attr("title", "Primeira página")
    $("#prev").attr("title", "Página anterior")
    $("#next").attr("title", "Próxima página")
    $("#last").attr("title", "Última página")

    // Adiciona efeito de hover nos botões
    $(".pagination-button").hover(
      function () {
        $(this).css("opacity", "0.7")
      },
      function () {
        $(this).css("opacity", "1")
      },
    )

    // Melhora a aparência do seletor de linhas por página
    $("#sel-linhas").addClass("form-select-sm")

    // Adiciona animação de fade-in para a tabela
    $("#table-recolhimentos").addClass("fade-in")

    // Configura o comportamento de alternância de cores nas linhas da tabela
    setupTableRowColors()

    // Configura os eventos de paginação
    setupPaginationEvents()

    // Garante que os alinhamentos dos cabeçalhos correspondam aos dados
    alignHeadersWithData()

    // Inicializa as abas de contato
    setupContactTabs()
  }

  // Configura as abas de contato
  function setupContactTabs() {
    // Configura o comportamento das abas
    $("#contactTabs a").on("click", function (e) {
      e.preventDefault()
      $(this).tab("show")
    })

    // Adiciona efeito de hover nas abas
    $("#contactTabs a").hover(
      function () {
        $(this).not(".active").css("background-color", "#f8f9fa")
      },
      function () {
        $(this).not(".active").css("background-color", "")
      },
    )
  }

  // Função para garantir que os cabeçalhos estejam alinhados com os dados
  function alignHeadersWithData() {
    // Centralizar todos os cabeçalhos
    $("thead th").addClass("text-center")

    // Alinhar todas as células do corpo à esquerda por padrão
    $("tbody td").addClass("text-start")

    // Exceções para células que precisam de alinhamento específico
    $("tbody td input[type='text'].text-end").parent().addClass("text-end")
    $("tbody td input[type='text'].text-center").parent().addClass("text-center")
    $("tbody td label.text-center").parent().addClass("text-center")
    $("tbody td label.text-end").parent().addClass("text-end")

    // Não ajustar larguras das colunas para permitir layout automático
    // Removido o código que definia larguras fixas
  }

  // Adicionar chamada para alinhar cabeçalhos após o carregamento da tabela
  $(window).on("load", () => {
    setTimeout(alignHeadersWithData, 500)
  })

  // Adicionar chamada para realinhar quando a janela for redimensionada
  $(window).on("resize", () => {
    alignHeadersWithData()
  })

  // Configura as cores alternadas das linhas da tabela
  function setupTableRowColors() {
    $("#tbody-recolhimentos tr:even").addClass("bg-light")
    $("#tbody-recolhimentos tr").hover(
      function () {
        $(this).addClass("bg-hover")
      },
      function () {
        $(this).removeClass("bg-hover")
      },
    )
  }

  // Configura os eventos dos botões de paginação
  function setupPaginationEvents() {
    $("#first").click(() => {
      if (npage > 1) {
        npage = 1
        $("#sel-page").val(npage)
        reloadTableData()
      }
    })

    $("#prev").click(() => {
      if (npage > 1) {
        npage--
        $("#sel-page").val(npage)
        reloadTableData()
      }
    })

    $("#next").click(() => {
      var maxPages = Math.ceil(Number.parseInt(total_records.val()) / nrows)
      if (npage < maxPages) {
        npage++
        $("#sel-page").val(npage)
        reloadTableData()
      }
    })

    $("#last").click(() => {
      var maxPages = Math.ceil(Number.parseInt(total_records.val()) / nrows)
      if (npage < maxPages) {
        npage = maxPages
        $("#sel-page").val(npage)
        reloadTableData()
      }
    })

    // Evento para mudança direta da página
    $("#sel-page").change(function () {
      npage = Number.parseInt($(this).val())
      reloadTableData()
    })

    // Evento para mudança de linhas por página
    $("#sel-linhas").change(function () {
      nrows = Number.parseInt($(this).val())
      npage = 1
      $("#sel-page").val(npage)
      reloadTableData()
    })
  }

  // Função para recarregar os dados da tabela
  function reloadTableData() {
    waiting_recolhimento.css("display", "block")
    $("#tbody-recolhimentos").empty()

    $.ajax({
      url: "modules/ler_recolhimentos.php",
      type: "POST",
      data: {
        id_user: id_user,
        tp_user: tp_user,
        id_muni: id_muni,
        dt_compet: dt_compet,
        npage: npage,
        nrows: nrows,
      },
      success: (response) => {
        processTableData(response)
      },
      error: (xhr, status, error) => {
        console.error("Erro ao recarregar dados:", error)
        waiting_recolhimento.css("display", "none")
      },
    })
  }

  // Processa os dados da tabela recebidos do servidor
  function processTableData(response) {
    var resp = JSON.parse(response)
    var erro = resp.Error
    var message = resp.Message

    if (erro === "0") {
      var data = JSON.parse(resp.Data)
      var ntotal = Number.parseInt(resp.Total_Records)
      total_records.val(ntotal)

      waiting_recolhimento.css("display", "none")

      const tbody_recolhimento = $(document.querySelector("#tbody-recolhimentos"))
      tbody_recolhimento.empty()

      data.forEach((elem, ix) => {
        var obj_row = $(elem)
        tbody_recolhimento.append(obj_row)
      })

      setupTableRowColors()
      setupTableEventHandlers()
      alignHeadersWithData()

      // Atualiza a informação de paginação
      updatePaginationInfo(ntotal)
    } else {
      console.error("Erro ao processar dados:", message)
      waiting_recolhimento.css("display", "none")
    }
  }

  // Atualiza a informação de paginação
  function updatePaginationInfo(ntotal) {
    var maxPages = Math.ceil(ntotal / nrows)

    // Desabilita botões de paginação conforme necessário
    if (npage <= 1) {
      $("#first, #prev").addClass("disabled").css("opacity", "0.5")
    } else {
      $("#first, #prev").removeClass("disabled").css("opacity", "1")
    }

    if (npage >= maxPages) {
      $("#next, #last").addClass("disabled").css("opacity", "0.5")
    } else {
      $("#next, #last").removeClass("disabled").css("opacity", "1")
    }

    // Atualiza o texto de paginação
    var startRecord = (npage - 1) * nrows + 1
    var endRecord = Math.min(npage * nrows, ntotal)

    // Se houver um elemento para mostrar a informação de paginação
    if ($("#pagination-info").length) {
      $("#pagination-info").text(`Mostrando ${startRecord}-${endRecord} de ${ntotal}`)
    }
  }

  // Configura os manipuladores de eventos para a tabela
  function setupTableEventHandlers() {
    var vlist_tr = $("#tbody-recolhimentos").children("tr")

    vlist_tr.each((ix, elem) => {
      var base_ISS = $(elem).children("td").eq(8)
      var cd_tipoagencia = base_ISS.prop("data-cd_tipoagencia") || base_ISS.data("cd_tipoagencia")
      var label_vlbase = base_ISS.find('label[id^="vl_baseISS-"]')

      if (label_vlbase.length > 0) {
        var id_agencia = label_vlbase.attr("id").replace(/\D+/, "")
        var vl_baseISS = label_vlbase.text()

        if (vl_baseISS.length > 0) {
          try {
            vl_baseISS = Number.parseFloat(vl_baseISS.replace(".", "").replace(",", "."))
          } catch (e) {
            console.error("Erro ao converter valor do ISS:", e)
            vl_baseISS = 0.0
          }

          if (vl_baseISS > 0.0 && cd_tipoagencia == "T") {
            base_ISS.on("click", (e) => {
              e.preventDefault()
              e.stopPropagation()

              showNotasModal(id_agencia, dt_compet)
            })
          }
        }
      }
    })

    // Adiciona eventos para os campos de valor para formatar automaticamente
    $('input[id^="vl_"]').on("blur", function () {
      formatCurrencyField($(this))
    })

    // Adiciona eventos para os selects para destacar mudanças
    $("select").on("change", function () {
      $(this).addClass("changed-field")
    })
  }

  // Formata um campo de valor como moeda
  function formatCurrencyField($field) {
    var value = $field.val().replace(/\./g, "").replace(",", ".")

    try {
      value = Number.parseFloat(value)
      if (isNaN(value)) value = 0

      $field.val(formatCurrency(value))
    } catch (e) {
      console.error("Erro ao formatar campo de moeda:", e)
    }
  }

  // Formata um valor como moeda
  function formatCurrency(value) {
    return value.toLocaleString("pt-BR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  }

  // Exibe o modal de notas
  function showNotasModal(id_agencia, dt_compet) {
    var payload = {
      id_agencia: id_agencia,
      dt_compet: dt_compet,
    }

    $.ajax({
      url: "views/tela_notas.php",
      type: "POST",
      data: payload,
      success: (response) => {
        div_modal.html("")

        setTimeout(() => {
          div_modal.html(response)
          const modal_notas = $(document.querySelector("#modal-notas"))

          if (modal_notas.length > 0) {
            const btn_modal = $(
              '<button type="button" data-toggle="modal" data-target="#modal-notas" style="display:none"/>',
            )
            div_modal.append(btn_modal)
            btn_modal.trigger("click")

            const bt_fechar = div_modal.children().find("button.btn-close")
            bt_fechar.on("click", (e) => {
              e.preventDefault()
              e.stopPropagation()

              modal_notas.modal("hide")
              $(".modal-backdrop").remove()
              $("body").removeClass(".modal-page")
              $("body").removeClass(".modal-open")
              $("body").removeClass(".modal-show")
              $("body").removeClass(".modal-dialog-scrollable")
              $("body").removeClass(".modal-dialog-centered")
              $("body").removeClass(".modal-static")
              $("body").removeClass(".modal-dialog-centered")
              $("body").css("padding", 0)
              div_modal.remove(btn_modal)
              div_modal.html("")
            })
          }
        }, 1000)
      },
      error: (xhr, status, error) => {
        console.error("Erro ao carregar dados do recolhimento:", error)
      },
    })
  }

  // rotina padrão para fixar head da tabela na tela
  function tableFixHead(evt) {
    const el = evt.currentTarget,
      sT = el.scrollTop
    el.querySelectorAll("thead th").forEach((th) => (th.style.top = `0px`))
  }

  document.querySelectorAll(".tableFixHead").forEach((el) => el.addEventListener("scroll", tableFixHead))

  const tbody_recolhimento = $(document.querySelector("#tbody-recolhimentos"))

  $.ajax({
    url: "modules/ler_recolhimentos.php",
    type: "POST",
    data: {
      id_user: id_user,
      tp_user: tp_user,
      id_muni: id_muni,
      dt_compet: dt_compet,
      npage: npage,
      nrows: nrows,
    },
    success: (response) => {
      processTableData(response)
    },
    error: (xhr, status, error) => {
      console.log(xhr.responseText)
      console.error("Erro ao carregar o modal:", error)
      waiting_recolhimento.css("display", "none")
    },
  })

  /*
        retorna o numero de linhas da tela
    */
  function getnRows() {
    const sel_linhas = tfoot.children().find("#sel-linhas > option:selected")
    nrows = sel_linhas.val()
    nrows = Number.parseInt(nrows)
    return nrows
  }

  function mudarFormato(data) {
    if (!data) return ""
    var partes = data.split("/")
    return partes[1] + "-" + partes[0]
  }

  // Função para salvar contato da agência
  window.salvarContatoAgencia = () => {
    // Implementar a lógica de salvamento aqui
    alert("Dados salvos com sucesso!")
  }
})

$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1050 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack')
            .css('z-index', zIndex - 1)
            .addClass('modal-stack');
    }, 0);
});
