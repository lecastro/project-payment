"use client";
import React, { useState, ChangeEvent, useEffect } from 'react';
import { initMercadoPago, getInstallments } from '@mercadopago/sdk-react';
import { createCardToken } from '@mercadopago/sdk-react/coreMethods';
import { PayerDataInterface } from '@/components/Contract/PayerDataInterface';
import { CardDataInterface } from '@/components/Contract/CardDataInterface';
import InputMask from 'react-input-mask';
import { NumberInstallmentInterface } from '@/components/Contract/NumberInstallmentInterface';

export default function Home() {
  initMercadoPago('TEST-4b65d9bb-27ef-4c41-be26-1901bd58065b');

  const [payerData, setPayerData] = useState<PayerDataInterface>({
    email: '',
    document: '',
    typeDocument: 'CPF'
  });

  const [cardData, setCardData] = useState<CardDataInterface>({
    amount: '',
    numberCard: '',
    titleCard: '',
    monthExpiration: '',
    yearExpiration: '',
    cvv: '',
    installments: '1'
  });

  const handlePayerChange = (e: ChangeEvent<HTMLInputElement> | ChangeEvent<HTMLSelectElement>) => {
    const { id, value } = e.target;
    setPayerData({
      ...payerData,
      [id]: value
    })
  }

  const handleCardChange = (e: ChangeEvent<HTMLInputElement> | ChangeEvent<HTMLSelectElement>) => {
    const { id, value } = e.target;
    setCardData({
      ...cardData,
      [id]: value
    })
  }

  const [numberInstallment, setNumberInstallment] = useState<any[] | undefined>([]);
  const [paymentMethodId, setPaymentMethodId] = useState<string | undefined>();

  const [loading, setLoading] = useState<boolean>(false)

  useEffect(() => {
    const fetchInstallments = async () => {
      try {
        setLoading(true);
        const amount = cardData.amount;
        const trimmedCardNumber = cardData.numberCard.replace(/\s/g, '');

        if (trimmedCardNumber.length === 16) {
          const bin = trimmedCardNumber.substring(0, 6);

          const result = await getInstallments({
            amount: amount,
            locale: 'pt-BR',
            bin: bin
          })

          const numberInstallment: NumberInstallmentInterface[] = [];

          result?.forEach((item) => {
            setPaymentMethodId(item.payment_method_id);
            numberInstallment.push(
              ...item.payer_costs.map((cost: any) => ({
                installments: cost.installments,
                recommendedMessage: cost.recommended_message
              })));
          });

          setNumberInstallment(numberInstallment);
        }
      } catch (error) {
        console.error('Error getting installments:', error);
        setLoading(false);
      }
    }

    if (cardData.amount && cardData.numberCard) {
      fetchInstallments();
    }

  }, [cardData.amount, cardData.numberCard]);

  const createToken = async (cardData: CardDataInterface, payerData: PayerDataInterface): Promise<string | undefined> => {
    try {
      const cardToken = await createCardToken({
        cardNumber: cardData.numberCard.replace(/\s/g, ''),
        cardholderName: cardData.titleCard,
        cardExpirationMonth: cardData.monthExpiration,
        cardExpirationYear: cardData.yearExpiration,
        securityCode: cardData.cvv,
        identificationType: payerData.typeDocument,
        identificationNumber: payerData.document,
      });
      return cardToken?.id;
    } catch (error) {
      console.error('Error generating token:', error);
      return undefined;
    }
  };

  const sendPaymentDataToBackend = async (dataToSend: any) => {
    try {
      const response = await fetch('http://localhost/rest/payments', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataToSend)
      });

      if (!response.ok) {
        throw new Error('Error processing payment.');
      }

      const responseData = await response.json();

      console.log('Backend response:', responseData);
    } catch (error) {
      console.error('Error when making request to the backend:', error);
    }
  };

  const handlePayment = async (e: React.MouseEvent<HTMLButtonElement>) => {
    e.preventDefault();

    const cardDataValues = Object.values(cardData);

    const payerDataValues = Object.values(payerData);

    const cardDataFilled = cardDataValues.every(value => value);

    const payerDataFilled = payerDataValues.every(value => value);

    if (!cardDataFilled && !payerDataFilled) {
      console.error('Please fill in all fields before making payment.');
      return;
    }

    const tokenCard = await createToken(cardData, payerData);

    if (tokenCard == undefined) {
      console.error('Error, creating card token.');
      return;
    }

    const dataToSend = {
      transaction_amount: parseFloat(cardData.amount),
      installments: parseInt(cardData.installments),
      token: tokenCard,
      payment_method_id: paymentMethodId,
      payer: {
        email: payerData.email,
        identification: {
          type: payerData.typeDocument,
          number: payerData.document
        }
      }
    };

    console.log('Dados do Pagador:', JSON.stringify(dataToSend));

    sendPaymentDataToBackend(dataToSend);
  }

  return (
    <div className="flex justify-center mt-8">
      <div className="border border-gradient rounded-lg p-4">
        <div className="flex flex-row">
          <div className="bg-white shadow-md rounded p-4 mb-4 mr-4">
            <h2 className="text-xl mb-4">Dados do Pagador</h2>
            <div className="mb-4">
              <input
                onChange={handlePayerChange}
                value={payerData.email}
                id='email'
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="email"
                placeholder="Email Dados do Pagador" />
            </div>
            <div className="mb-4">
              <select
                onChange={handlePayerChange}
                value={payerData.typeDocument}
                id='typeDocument'
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="CPF">CPF</option>
                <option value="CNPJ">CNPJ</option>
              </select>
            </div>

            <div className="mb-6">
              <input
                onChange={handlePayerChange}
                value={payerData.document}
                id='document'
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="text"
                placeholder="Número de identificação" />
            </div>
          </div>
          <div className="bg-white shadow-md rounded p-4 mb-4">
            <h2 className="text-xl mb-4">Dados do Pagamento</h2>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="valor">
                Valor do Pagamento
              </label>
              <input
                onChange={handleCardChange}
                value={cardData.amount}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="amount"
                type="text"
                placeholder="Valor"
                name='amount' />
            </div>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="numero_cartao">
                Número do Cartão
              </label>
              <input
                onChange={handleCardChange}
                value={cardData.numberCard}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="numberCard"
                type="text"
                placeholder="Número do Cartão"
              />
            </div>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="nome_titular">
                Nome do Titular
              </label>
              <input
                onChange={handleCardChange}
                value={cardData.titleCard}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="titleCard"
                type="text"
                placeholder="Nome do Titular" />
            </div>
            <div className="flex flex-col mb-4">
              <div className="mb-4">
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="mes_expiracao">
                  Mês de Expiração
                </label>
                <InputMask
                  mask="99"
                  onChange={handleCardChange}
                  value={cardData.monthExpiration}
                  className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  id="monthExpiration"
                  placeholder="Mês"
                  min="01"
                  max="12"
                />
              </div>
              <div>
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="ano_expiracao">
                  Ano de Expiração
                </label>
                <InputMask
                  mask="9999"
                  onChange={handleCardChange}
                  value={cardData.yearExpiration}
                  className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  id="yearExpiration"
                  placeholder="Ano"
                  min={new Date().getFullYear()}
                  max={new Date().getFullYear() + 10}
                />
              </div>
            </div>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="cvv">
                CVV
              </label>
              <input
                onChange={handleCardChange}
                value={cardData.cvv}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="cvv"
                type="text"
                placeholder="CVV" />
            </div>
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="parcelas">
                Selecione o número de Parcelas
              </label>
              <select
                onChange={handleCardChange}
                value={cardData.installments}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="installments"
              >
                {numberInstallment && numberInstallment.length > 0 ? (
                  numberInstallment.map((option, index) => (
                    <option key={index} value={option.installments}>
                      {option.recommendedMessage}
                    </option>
                  ))
                ) : (
                  <option disabled value="">
                    Sem opções de parcelamento disponíveis
                  </option>
                )}
              </select>
            </div>
          </div>
        </div>
        <div className="flex justify-center">
          <button
            onClick={handlePayment}
            className="bg-green-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Pagar
          </button>
        </div>
      </div>
    </div>
  );
}
