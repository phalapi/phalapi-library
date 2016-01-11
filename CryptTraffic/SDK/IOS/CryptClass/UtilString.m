//
//  UtilString.m
//  EASY vision 1.0
//
//  Created by 葛新伟 on 15/10/28.
//  Copyright © 2015年 Xuyang Gordon Wang. All rights reserved.
//

#import "UtilString.h"

@implementation UtilString

/**
 *  获取urlencode string
 *
 *  @param baseStr 原string
 *
 *  @return 编码后的string
 */
+ (NSString *)stringByURLEncode:(NSString *)baseStr {
    CFStringRef enStr = CFURLCreateStringByAddingPercentEscapes(kCFAllocatorDefault,
                                                                (CFStringRef)baseStr,
                                                                (CFStringRef)@"!$&'()*+,-./:;=?@_~%#[]",
                                                                NULL,
                                                                kCFStringEncodingUTF8);
    NSString * reStr = (__bridge NSString *)enStr;
    CFRelease(enStr);
    return reStr;
}

/**
 *  转换成json string
 *
 *  @param object 需要转换的对象
 *
 *  @return 转换后的jsonstring
 */
+ (NSString*)DataTOjsonString:(id)object {
    NSString *jsonString = nil;
    NSError *error;
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:object
                                                       options:NSJSONWritingPrettyPrinted // Pass 0 if you don't care about the readability of the generated string
                                                         error:&error];
    if (! jsonData) {
        NSLog(@"Got an error: %@", error);
    } else {
        jsonString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    }
    return jsonString;
}

/**
 *  把json string 转换成对象
 *
 *  @param json json string
 *
 *  @return 转换后的对象
 */
+ (id)jsonToData:(NSString *)json{
    NSError *error;
    id reObjc = [NSJSONSerialization JSONObjectWithData:[json dataUsingEncoding:NSUTF8StringEncoding] options:NSJSONReadingAllowFragments error:&error];
    if (!reObjc) {
        NSLog(@"Got an error: %@", error);
        return nil;
    }else{
        return reObjc;
    }
}

@end
