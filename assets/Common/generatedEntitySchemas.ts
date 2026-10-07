import chartPoint from '../data/entity/chart_point.json';

type EntitySchema = { name: string };

export default function getGeneratedEntitySchemas(): Record<string, EntitySchema> {
  return {
    [chartPoint.name]: chartPoint,
  };
}
