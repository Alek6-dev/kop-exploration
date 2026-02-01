const getClassBonusImpact = (refValue: number, value: number) => {
    if(value > refValue) {
        return "impact-positive"
    }
    if(value < refValue) {
        return "impact-negative"
    }
    return ""
}

export default getClassBonusImpact;
