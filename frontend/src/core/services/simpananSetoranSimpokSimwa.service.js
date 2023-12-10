/**
 * BEGIN
 * @author Irham C. <icip1998@gmail.com>
 */
import axios from "./axiosConfig";

export default {
  listSimpananSetoranSimpokSimwa(payload, token) {
    const url = `trx_member/read_setoran_pokwa`;
    const config = {
      headers: {
        token: token,
      },
    };
    return axios.post(url, payload, config);
  },
  createSimpananSetoranSimpokSimwa(payload, token) {
    const url = `trx_member/transaksi_setoran_pokwa`;
    const config = {
      headers: {
        token: token,
      },
    };
    return axios.post(url, payload, config);
  },
};
