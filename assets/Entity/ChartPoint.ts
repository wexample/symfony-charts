import AbstractApiEntity from '@wexample/js-api-entity/Common/AbstractApiEntity';
import schema from '../data/entity/chart_point.json';

export default class ChartPoint extends AbstractApiEntity {
  static readonly entityName = 'chartPoint';

  static retrieveEntitySchema() {
    return schema;
  }
}
