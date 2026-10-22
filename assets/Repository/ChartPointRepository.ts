import AbstractApiRepository from '@wexample/js-api-entity/Common/AbstractApiRepository';
import ChartPoint from '../Entity/ChartPoint.js';

export default class ChartPointRepository extends AbstractApiRepository<ChartPoint> {
  static getEntityType() {
    return ChartPoint;
  }
}
